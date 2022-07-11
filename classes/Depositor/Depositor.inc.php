<?php
namespace Optimeta\Citations\Depositor;

import('plugins.generic.optimetaCitations.classes.Helpers');
import('plugins.generic.optimetaCitations.classes.Model.AuthorModel');
import('plugins.generic.optimetaCitations.classes.Model.WorkModel');
import('plugins.generic.optimetaCitations.classes.Depositor.OpenCitations');

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
        $citations = [];
        $citationsSubmitted = [];

        // return if input is empty
        if (empty($citationsParsed)) { return $citations; }

        // OpenCitations
        $openCitations = new OpenCitations();
        $citations = $openCitations->submitWork($submissionId, $citationsParsed);

        return $citations;
    }


}