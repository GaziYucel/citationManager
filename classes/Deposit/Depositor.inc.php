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

class Depositor
{
    /**
     * @desc Submit enriched citations and return citations
     * @param string $submissionId
     * @param array $citationsParsed
     * @return array $citations
     */
    public function executeAndReturnCitations(string $submissionId, array $citationsParsed): array
    {
        $response = [
            'opencitations_url' => '-1-'
        ];

        // return if input is empty
        if (empty($citationsParsed)) { return $response; }

        // OpenCitations
        $openCitations = new OpenCitations();
        $response['opencitations_url'] = $openCitations->submitWork($submissionId, $citationsParsed);

        return $response;
    }


}