<?php
namespace Optimeta\Citations\Submitter;

import('plugins.generic.optimetaCitations.classes.Helpers');
import('plugins.generic.optimetaCitations.classes.Model.AuthorModel');
import('plugins.generic.optimetaCitations.classes.Model.WorkModel');
import('plugins.generic.optimetaCitations.classes.Submitter.OpenCitations');

class Submitter
{
    /**
     * @desc Submit enriched citations and return citations
     * @param array $citationsParsed
     * @return array $citations
     * @throws \GuzzleHttp\Exception\GuzzleException
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