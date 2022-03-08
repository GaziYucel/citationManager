<?php
namespace Optimeta\Citations\Parser;

import('plugins.generic.optimetaCitations.classes.Model.CitationModel');
import('plugins.generic.optimetaCitations.classes.Parser.ParserBase');
import('plugins.generic.optimetaCitations.classes.Parser.ParserDOI');
import('plugins.generic.optimetaCitations.classes.Parser.ParserURL');

use Optimeta\Citations\Model\CitationModel;
//use Optimeta\Citations\Parser\ParserBase;
//use Optimeta\Citations\Parser\ParserDOI;
//use Optimeta\Citations\Parser\ParserURL;

class Parser extends ParserBase
{
    /**
     * Returns parsed citations as an array
     *
     * @return array citationsParsed
     */
    public function getCitationsParsedArray(): array
    {
        $this->parse();

        return $this->citationsParsed;
    }

    /**
     * Returns parsed citations as an JSON
     *
     * @return string (json) citationsParsed
     */
    public function getCitationsParsedJson(): string
    {
        $this->parse();

        if (sizeof($this->citationsParsed) == 0) {
            return '[]';
        }

        return json_encode($this->citationsParsed);
    }

    /**
     * Parse and return parsed citations as an array
     * Assumed that raw citations are separated with line endings
     *
     * @return void
     */
    private function parse(): void
    {
        // strip whitespace
        $citationsRaw = trim($this->citationsRaw);

        // strip slashes
        $citationsRaw = stripslashes($citationsRaw);

        // remove empty lines and normalize line endings.
        $citationsRaw = preg_replace('/[\r\n]+/s', "\n", $citationsRaw);

        // remove trailing/leading line breaks.
        $citationsRaw = trim($citationsRaw, "\n");

        // return if input is empty
        if (empty($citationsRaw)) {
            return;
        }

        // break up at line endings
        $citationsArray = explode("\n", $citationsRaw);

        // loop through citations and parse every citation
        foreach ($citationsArray as $index => $rowRaw) {

            // clean single citation
            $rowRaw = $this->cleanCitationString($rowRaw);

            // remove numbers from the beginning of each citation
            $rowRaw = $this->removeLeadingNumbersFromBeginning($rowRaw);

            // get data model and fill empty objRowParsed
            $objRowParsed = new CitationModel();

            // doi parser
            $doiParser = new ParserDOI();
            $objDoi = $doiParser->getParsed($rowRaw); // OptimetaCitationsCitationModel
            $objRowParsed->doi = $objDoi->doi;
            $objRowParsed->rawRemainder = $this->cleanCitationString($objDoi->rawRemainder);

            // url parser (after parsing doi)
            $urlParser = new ParserURL();
            $objUrl = $urlParser->getParsed($objRowParsed->rawRemainder); // OptimetaCitationsCitationModel
            $objRowParsed->url = $objUrl->url;
            $objRowParsed->rawRemainder = $this->cleanCitationString($objUrl->rawRemainder);

            $objRowParsed->raw = $rowRaw;

            // push to citations parsed array
            $this->citationsParsed[] = (array)$objRowParsed;
        }
    }


}
