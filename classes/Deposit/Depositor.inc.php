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

import('plugins.generic.optimetaCitations.classes.Deposit.OpenCitations');
import('plugins.generic.optimetaCitations.classes.Model.WorkModelHelpers');

use Optimeta\Citations\Model\WorkModelHelpers;

class Depositor
{
    /**
     * @desc Submit enriched citations and return citations
     * @param string $submissionId
     * @param array $citationsParsed
     * @return array $citations
     */
    public function executeAndReturnCitations(string $submissionId, array $citationsParsed)
    {
        $submissionDao = \DAORegistry::getDAO('SubmissionDAO');
        $submission = $submissionDao->getById($submissionId);
        $publication = $submission->getLatestPublication();

        $publicationDao = \DAORegistry::getDAO('PublicationDAO');
        $publication = $publicationDao->getById($submission->getLatestPublication()->getId());

        $publicationWorkDb = $publication->getData(OPTIMETA_CITATIONS_PUBLICATION_WORK);

        $publicationWork = WorkModelHelpers::getModelAsArrayNullValues();

        if(!empty($publicationWorkDb) && $publicationWorkDb !== '[]'){
            $publicationWork = json_decode($publicationWorkDb, true);
        }

        // return if input is empty
        if (empty($citationsParsed)) { return $publicationWork; }

        // OpenCitations
        $openCitations = new OpenCitations();
        $openCitationsUrl = $openCitations->submitWork($submissionId, $citationsParsed);

        $publicationWork['opencitations_url'] = $openCitationsUrl;

        $publicationWorkJson = json_encode($publicationWork);

        // save to database
        $publication->setData(OPTIMETA_CITATIONS_PUBLICATION_WORK, $publicationWorkJson);
        $publicationDao->updateObject($publication);

        return $publicationWork;
    }
}