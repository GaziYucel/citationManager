<?php
/**
 * @file plugins/generic/optimetaCitations/classes/Deposit/Depositor.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Depositor
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Main Depositor class
 */

namespace APP\plugins\generic\optimetaCitations\classes\Deposit;

use APP\core\Application;
use APP\core\Services;
use APP\plugins\generic\optimetaCitations\classes\Dao\PluginDAO;
use APP\plugins\generic\optimetaCitations\classes\Model\WorkModel;
use APP\plugins\generic\optimetaCitations\OptimetaCitationsPlugin;
use APP\submission\Submission;
use Exception;
use PKP\db\DAORegistry;

class Depositor
{
    /**
     * Is this instance production
     *
     * @var bool
     */
    protected bool $isProduction = false;

    /**
     * @var OptimetaCitationsPlugin
     */
    protected OptimetaCitationsPlugin $plugin;

    /**
     * Log string
     *
     * @var string
     */
    public string $log = '';

    public function __construct(OptimetaCitationsPlugin $plugin)
    {
        $this->plugin = $plugin;

        if ($this->plugin->getSetting($this->plugin->getCurrentContextId(),
                $this->plugin::OPTIMETA_CITATIONS_IS_PRODUCTION_KEY) === 'true') {
            $this->isProduction = true;
        }
    }

    /**
     * Submit enriched citations and return citations
     *
     * @param string $submissionId
     * @param array $citationsParsed
     * @param bool $isBatch
     * @return array
     * @throws Exception
     */
    public function executeAndReturnWork(string $submissionId, array $citationsParsed, bool $isBatch = false): array
    {
        $publicationWork = get_object_vars(new WorkModel());

        // return if input or username password is empty
        if (empty($submissionId) || empty($citationsParsed))
            return $publicationWork;

        // request
        $request = $this->plugin->getRequest();

        // context or journal
        $context = $request->getContext();

        // submission
        $submissionDao = DAORegistry::getDAO('SubmissionDAO');
        $submission = $submissionDao->getById($submissionId);

        // return if doi is empty
        if (empty($submission->getStoredPubId('doi')))
            return $publicationWork;

        // publication
        $publicationDao = DAORegistry::getDAO('PublicationDAO');
        $publication = $submission->getLatestPublication();

        // authors
        $authors = $submission->getAuthors();

        // issue
        $issueId = $publication->getData('issueId');
        $issue = null;
        $issueDao = DAORegistry::getDAO('IssueDAO');
        if (!is_null($issueDao->getById($issueId))) {
            $issue = $issueDao->getById($issueId);
        }

        // publication work
        $publicationWorkDb = $publication->getData($this->plugin::OPTIMETA_CITATIONS_PUBLICATION_WORK);
        if (!empty($publicationWorkDb) && $publicationWorkDb !== '[]')
            $publicationWork = json_decode($publicationWorkDb, true);

        $doi = $submission->getStoredPubId('doi');

        // OpenCitations
        if (empty($publicationWork['opencitations_url']) && !empty($doi) && !empty($issue)) {
            $openCitations = new OpenCitations($this->plugin);
            $openCitationsUrl = $openCitations->submitWork(
                $context,
                $issue,
                $submission,
                $publication,
                $authors,
                $publicationWork,
                $citationsParsed);
            $publicationWork['opencitations_url'] = $openCitationsUrl;

            $this->log .= '[openCitationsUrl: ' . $openCitationsUrl . ']';
        }

        // WikiData: proceed if url empty, username and password given
        if (empty($publicationWork['wikidata_url']) && !empty($doi) && !empty($issue)) {
            $wikiData = new WikiData($this->plugin);
            $wikiDataQid = $wikiData->submitWork(
                $context,
                $issue,
                $submission,
                $publication,
                $authors,
                $publicationWork,
                $citationsParsed);

            $publicationWork['wikidata_qid'] = $wikiDataQid;
            if (!empty($wikiDataQid))
                $publicationWork['wikidata_url'] = $this->plugin::OPTIMETA_CITATIONS_WIKIDATA_URL . '/' . $wikiDataQid;

            if (!$this->isProduction)
                $publicationWork['wikidata_url'] = $this->plugin::OPTIMETA_CITATIONS_WIKIDATA_URL_TEST . '/' . $wikiDataQid;

            $this->log .= '[publicationWork>wikidata_qid: ' . $publicationWork['wikidata_qid'] . ']';
            $this->log .= '[publicationWork>wikidata_url: ' . $publicationWork['wikidata_url'] . ']';
        }

        // convert to json
        $publicationWorkJson = json_encode($publicationWork);

        // save to database
        $publication->setData($this->plugin::OPTIMETA_CITATIONS_PUBLICATION_WORK, $publicationWorkJson);
        $publicationDao->updateObject($publication);

        return $publicationWork;
    }

    /**
     * Batch deposit
     *
     * @return bool
     * @throws Exception
     */
    public function batchDeposit(): bool
    {
        foreach ($this->getContextIds() as $contextId) {
            $this->log .= '[$contextId: ' . $contextId . ']';
            foreach ($this->getPublishedSubmissionIds($contextId) as $submissionId) {
                $this->log .= '[$submissionId: ' . $submissionId . ']';
                $submissionDao = DAORegistry::getDAO('SubmissionDAO');
                $submission = $submissionDao->getById($submissionId);
                $publication = $submission->getLatestPublication();

                $pluginDao = $this->plugin->pluginDao;
                $citations = $pluginDao->getCitations($publication);

                $publicationWork = $this->executeAndReturnWork($submissionId, $citations, true);

                $this->log .= '[$publicationWork>opencitations_url: ' . $publicationWork['opencitations_url'] . ']';
            }
        }

        return true;
    }

    /**
     * Get an array of all published submission IDs in the database
     *
     * @param int $contextId
     * @return array
     */
    public function getPublishedSubmissionIds(int $contextId): array
    {
        $submissionsIterator = Services::get('submission')->getMany([
            'contextId' => $contextId,
            'status' => Submission::STATUS_PUBLISHED]);

        $submissionIds = [];

        foreach ($submissionsIterator as $submission) {
            $submissionIds[] = $submission->getId();
        }

        return $submissionIds;
    }

    /**
     * Get an array of all context IDs in the database
     *
     * @return array
     */
    public function getContextIds(): array
    {
        $contextIds = [];

        $contextDao = Application::getContextDAO();
        $contextFactory = $contextDao->getAll();
        try {
            while ($context = $contextFactory->next()) {
                $contextIds[] = $context->getId();
            }
        } catch (Exception) {
        }

        return $contextIds;
    }

    function __destruct()
    {
        // error_log('Depositor->__destruct: ' . $this->log);
    }
}