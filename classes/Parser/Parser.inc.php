<?php
namespace Optimeta\Citations\Parser;

import('plugins.generic.optimetaCitations.classes.Helpers');
import('plugins.generic.optimetaCitations.classes.Model.CitationModel');
import('plugins.generic.optimetaCitations.classes.Parser.ParserDOI');
import('plugins.generic.optimetaCitations.classes.Parser.ParserURL');
import('plugins.generic.optimetaCitations.classes.Parser.ParserURN');

use Optimeta\Citations\Helpers;
use Optimeta\Citations\Model\CitationModel;

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

            // clean single citation
            $rowRaw = $this->cleanCitation($rowRaw);

            // remove numbers from the beginning of each citation
            $rowRaw = Helpers::removeNumberPrefixFromString($rowRaw);

            // get data model and fill empty objRowParsed
            $objRowParsed = new CitationModel();

            // doi parser
            $doiParser = new ParserDOI();
            $objDoi = $doiParser->getParsed($rowRaw); // CitationModel
            $objRowParsed->doi = $objDoi->doi;
            $objRowParsed->rawRemainder = $this->cleanCitation($objDoi->rawRemainder);

            // url parser (after parsing doi)
            $urlParser = new ParserURL();
            $objUrl = $urlParser->getParsed($objRowParsed->rawRemainder); // CitationModel
            $objRowParsed->url = $objUrl->url;
            $objRowParsed->rawRemainder = $this->cleanCitation($objUrl->rawRemainder);

            // urn parser
            $urnParser = new ParserURN();
            $objUrn = $urnParser->getParsed($objRowParsed->rawRemainder); // CitationModel
            $objRowParsed->urn = $objUrn->urn;
            $objRowParsed->rawRemainder = $this->cleanCitation($objUrn->rawRemainder);

            $objRowParsed->raw = $rowRaw;

            // push to citations parsed array
            $citations[] = (array)$objRowParsed;
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
