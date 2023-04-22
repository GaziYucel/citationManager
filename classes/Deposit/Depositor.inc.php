<?php
/**
 * @file plugins/generic/optimetaCitations/classes/Deposit/Depositor.inc.php
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

namespace Optimeta\Citations\Deposit;

use Optimeta\Citations\Dao\PluginDAO;
use Optimeta\Citations\Model\WorkModel;
use Services;

class Depositor
{
    /**
     * Log string
     * @var string
     */
    public string $log = '';

    /**
     * Submit enriched citations and return citations
     * @param string $submissionId
     * @param array $citationsParsed
     * @param bool $isBatch
     * @return array
     */
    public function executeAndReturnWork(string $submissionId, array $citationsParsed, bool $isBatch = false)
    {
        $publicationWork = get_object_vars(new WorkModel());

        // return if input is empty
        if (empty($submissionId) || empty($citationsParsed)) return $publicationWork;

        $submissionDao = \DAORegistry::getDAO('SubmissionDAO');
        $submission = $submissionDao->getById($submissionId);

        $publicationDao = \DAORegistry::getDAO('PublicationDAO');
        $publication = $publicationDao->getById($submission->getLatestPublication()->getId());

        $publicationWorkDb = $publication->getData(OPTIMETA_CITATIONS_PUBLICATION_WORK);
        if (!empty($publicationWorkDb) && $publicationWorkDb !== '[]')
            $publicationWork = json_decode($publicationWorkDb, true);

        // OpenCitations: deposit if not batch or empty
        if (!$isBatch || empty($publicationWork['opencitations_url'])) {
            $openCitations = new OpenCitations();
            $openCitationsUrl = $openCitations->submitWork($submissionId, $citationsParsed);
            $publicationWork['opencitations_url'] = $openCitationsUrl;
            $this->log .= '[$publicationWork>opencitations_url: ' .
                $publicationWork['opencitations_url'] . ']';
        }

        // WikiData: deposit if not batch or empty
        if (!$isBatch || empty($publicationWork['wikidata_url'])) {
            $wikiData = new WikiData();
            $wikiDataUrl = $wikiData->submitWork(
                $submissionId,
                $citationsParsed);
            $publicationWork['wikidata_url'] = $wikiDataUrl;
            $this->log .= '[$publicationWork>wikidata_url: ' .
                $publicationWork['wikidata_url'] . ']';
        }

        // convert to json
        $publicationWorkJson = json_encode($publicationWork);

        // save to database
        $publication->setData(OPTIMETA_CITATIONS_PUBLICATION_WORK, $publicationWorkJson);
        $publicationDao->updateObject($publication);

        return $publicationWork;
    }

    /**
     * Batch deposit
     * @return bool
     */
    public function batchDeposit(): bool
    {
        $result = true;

        foreach ($this->getContextIds() as $contextId) {
            $this->log .= '[$contextId: ' . $contextId . ']';
            foreach ($this->getPublishedSubmissionIds($contextId) as $submissionId) {
                $this->log .= '[$submissionId: ' . $submissionId . ']';
                $submissionDao = new \SubmissionDAO();
                $submission = $submissionDao->getById($submissionId);
                $publication = $submission->getLatestPublication();

                $pluginDao = new PluginDAO();
                $citations = $pluginDao->getCitations($publication);

                $publicationWork = $this->executeAndReturnWork($submissionId, $citations, true);

                $this->log .= '[$publicationWork>opencitations_url: ' . $publicationWork['opencitations_url'] . ']';
            }
        }

        return $result;
    }

    /**
     * Get an array of all published submission IDs in the database
     * @param int $contextId
     * @return array
     */
    public function getPublishedSubmissionIds($contextId)
    {
        import('classes.submission.Submission');
        $submissionsIterator = Services::get('submission')->getMany([
            'contextId' => $contextId,
            'status' => STATUS_PUBLISHED]);
        $submissionIds = [];
        foreach ($submissionsIterator as $submission) {
            $submissionIds[] = $submission->getId();
        }
        return $submissionIds;
    }

    /**
     * Get an array of all context IDs in the database
     * @return array
     */
    public function getContextIds()
    {
        $contextIds = [];

        $contextDao = \Application::getContextDAO();
        $contextFactory = $contextDao->getAll();
        try {
            while ($context = $contextFactory->next()) {
                $contextIds[] = $context->getId();
            }
        } catch (\Exception $exception) {
        }

        return $contextIds;
    }

    function __destruct()
    {
        error_log('Depositor->__destruct: ' . $this->log);
    }
}