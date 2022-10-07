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
 * @brief Depositor class
 *
 */
namespace Optimeta\Citations\Deposit;

use Optimeta\Citations\Dao\PluginDAO;
use Optimeta\Citations\Model\WorkModelHelpers;
use Services;

class Depositor
{
    /**
     * @desc Submit enriched citations and return citations
     * @param string $submissionId
     * @param array $citationsParsed
     * @return array $citations
     */
    public function executeAndReturnWork(string $submissionId, array $citationsParsed)
    {
        $publicationWork = WorkModelHelpers::getModelAsArrayNullValues();

        // return if input is empty
        if (empty($submissionId) || empty($citationsParsed)) {
            return $publicationWork;
        }

        $submissionDao = \DAORegistry::getDAO('SubmissionDAO');
        $submission = $submissionDao->getById($submissionId);
        $publication = $submission->getLatestPublication();

        $publicationDao = \DAORegistry::getDAO('PublicationDAO');
        $publication = $publicationDao->getById($submission->getLatestPublication()->getId());

        $publicationWorkDb = $publication->getData(OPTIMETA_CITATIONS_PUBLICATION_WORK);
        if(!empty($publicationWorkDb) && $publicationWorkDb !== '[]'){
            $publicationWork = json_decode($publicationWorkDb, true);
        }

        // OpenCitations
//        if(empty($publicationWork['opencitations_url'])){
            $openCitations = new OpenCitations();
            $openCitationsUrl = $openCitations->submitWork($submissionId, $citationsParsed);
            $publicationWork['opencitations_url'] = $openCitationsUrl;
//        }

        $publicationWorkJson = json_encode($publicationWork);

        // save to database
        $publication->setData(OPTIMETA_CITATIONS_PUBLICATION_WORK, $publicationWorkJson);
        $publicationDao->updateObject($publication);

        return $publicationWork;
    }

    public function batchDeposit(): bool
    {
        $result = true;

        foreach($this->getContextIds() as $contextId){
            $debug->Add('$contextId: ' . $contextId);
            foreach($this->getPublishedSubmissionIds($contextId) as $submissionId){
                $debug->Add('$submissionId: ' . $submissionId);

                $submissionDao = new \SubmissionDAO();
                $submission = $submissionDao->getById($submissionId);
                $publication = $submission->getLatestPublication();

                $pluginDao = new PluginDAO();
                $citations = $pluginDao->getCitations($publication);

                $publicationWork = $this->executeAndReturnWork($submissionId, $citations);

                $debug->Add('$publicationWork>opencitations_url: ' . $publicationWork['opencitations_url']);
            }
        }

        return $result;
    }

    /**
     * Get an array of all published submission IDs in the database
     */
    public function getPublishedSubmissionIds($contextId) {
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
     */
    public function getContextIds()
    {
        $contextIds = [];

        $contextDao = \Application::getContextDAO();
        $contextFactory = $contextDao->getAll();
        while ($context = $contextFactory->next()) {
            $contextIds[] = $context->getId();
        }

        return $contextIds;
    }
}