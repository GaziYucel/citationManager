<?php
namespace Optimeta\Citations\Parse;

import('plugins.generic.optimetaCitations.classes.Helpers');
import('plugins.generic.optimetaCitations.classes.Model.CitationModel');
import('plugins.generic.optimetaCitations.classes.Pid.Doi');
import('plugins.generic.optimetaCitations.classes.Pid.Url');
import('plugins.generic.optimetaCitations.classes.Pid.Urn');

use Optimeta\Citations\Helpers;
use Optimeta\Citations\Model\CitationModel;
use Optimeta\Citations\Pid\Doi;
use Optimeta\Citations\Pid\Url;
use Optimeta\Citations\Pid\Urn;

class Parser
{
    /**
     * @desc Parse and save parsed citations to citationsParsed
     * @param string $citationsRaw
     * @return array
     */
    public function executeAndReturnCitations(string $citationsRaw): array
    {
        $citations = [];

        // cleanup citationsRaw
        $citationsRaw = $this->cleanCitationsRaw($citationsRaw);

        // return if input is empty
        if (empty($citationsRaw)) { return $citations; }

        // break up at line endings
        $citationsArray = explode("\n", $citationsRaw);

        // loop through citations and parse every citation
        foreach ($citationsArray as $index => $rowRaw) {

            // get data model and fill citation raw
            $citation = new CitationModel();
            $citation->raw = $rowRaw;

            // clean single citation
            $citation->raw = $this->cleanCitation($citation->raw);

            // remove numbers from the beginning of each citation
            $citation->raw = Helpers::removeNumberPrefixFromString($citation->raw);

            // parse doi
            $objDoi = new Doi();
            $citation->doi = $objDoi->getDoiParsed($citation->raw);
            $citation->raw = $objDoi->normalizeDoi($citation->raw, $citation->doi);

            // parse url (after parsing doi)
            $objUrl = new Url();
            $citation->url = $objUrl->getUrlParsed(str_replace($citation->doi, '', $citation->raw));

            // urn parser
            $objUrn = new Urn();
            $citation->urn = $objUrn->getUrnParsed($citation->raw);

            // push to citations parsed array
            $citations[] = (array)$citation;
        }

        return $citations;
    }

    /**
     * @desc Clean and return citationRaw
     * @param string $citationsRaw
     * @return string
     */
    private function cleanCitationsRaw(string $citationsRaw): string
    {
        // strip whitespace
        $citationsRaw = trim($citationsRaw);

        // strip slashes
        $citationsRaw = stripslashes($citationsRaw);

        // normalize line endings.
        $citationsRaw = Helpers::normalizeLineEndings($citationsRaw);

        // remove trailing/leading line breaks.
        $citationsRaw = trim($citationsRaw, "\n");

        return $citationsRaw;
    }

    /**
     * @desc Clean and return citation
     * @param $citation
     * @return string
     */
    private function cleanCitation($citation): string
    {
        // strip whitespace
        $citation = trim($citation);

        // trim .,
        $citation = trim($citation, '.,');

        // strip slashes
        $citation = stripslashes($citation);

        // normalize whitespace
        $citation = Helpers::normalizeWhiteSpace($citation);

        return $citation;
    }
}
