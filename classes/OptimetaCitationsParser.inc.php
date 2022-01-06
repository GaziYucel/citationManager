<?php

/**
 * @file plugins/generic/optimetaCitations/classes/OptimetaCitationsParser.inc.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class OptimetaCitationsParser
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Class for parsing citations
 */

class OptimetaCitationsParser
{
    /**
     * Basis URL of doi
     *
     * @var string
     */
    private string $doiUrl = 'doi.org';

    /**
     * Regex to extract DOI
     *
     * @var string
     */
    // '/^10.\d{4,9}/[-._;()/:A-Z0-9]+$/i'; // crossref regex, doesn't work
    private string $regexDoi = '(10[.][0-9]{4,}[^\s"/<>]*/[^\s"<>]+)';

    /**
     * Variable which will hold the raw citations
     *
     * @var string
     */
    private string $rawCitations = "";

    /**
     * Array which hold the parsed citations: ( ( "pid" => "pid1", "raw" => "raw1" ), ... )
     *
     * @var array
     */
    private array $parsedCitations = [];

    /**
     * Constructor.
     * @param $rawCitations string an unparsed citation string
     */
    function __construct(string $rawCitations = "")
    {
        $this->rawCitations= $rawCitations;
    }

    /**
     * Returns parsed citations as an array
     *
     * @return array parsedCitations [ (doi1, citation1), (doi2, citations2), ... ]
     */
    public function getParsedCitationsArray(): array
    {
        $this->parse();
        return $this->parsedCitations;
    }

    /**
     * Returns parsed citations as an JSON
     *
     * @return string (json) parsedCitations
     */
    public function getParsedCitationsJson(): string
    {
        $this->parse();
        return json_encode($this->parsedCitations);
    }

    /**
     * Parse and return parsed citations as an array
     * Assumed that raw citations are separated with line endings
     *
     * @param $citationsRaw string
     * @return void
     */
    private function parse(): void
    {
        $citationsRaw = $this->rawCitations;
        $citationsArray = [];

        // Strip whitespace
        $citationsRaw = trim($citationsRaw);

        // Strip slashes
        $citationsRaw = stripslashes($citationsRaw);

        // Remove empty lines and normalize line endings.
        $citationsRaw = preg_replace('/[\r\n]+/s', "\n", $citationsRaw);

        // Remove trailing/leading line breaks.
        $citationsRaw = trim($citationsRaw, "\n");

        // Return if input is empty
        if(empty($citationsRaw)) { return; }

        // input not empty, continue parsing

        // Break up at line endings.
        $citationsArray = explode("\n", $citationsRaw);

        // Extract PID/ DOI from every citation
        foreach($citationsArray as $index => $citationRawLine) {

            // Clean single citation
            $citationRawLine = $this->cleanCitationString($citationRawLine);

            // Remove numbers from the beginning of each citation
            $citationRawLine = $this->removeLeadingNumbersFromBeginning($citationRawLine);

            // match pid / doi in citation
            $pid = '';
            $raw = $citationRawLine;
            $doiArray = [];
            preg_match($this->regexDoi, $raw, $doiArray);

            if(!empty($doiArray[0])) $pid = $doiArray[0];

            if(!empty($pid))
            {
                $raw = str_replace(
                    'http://' . $this->doiUrl . '/',
                    'https://' . $this->doiUrl . '/', $raw);
                $pid = 'https://' . $this->doiUrl . '/' . $pid;

                if(stristr($raw, $pid)){
                    $raw = trim(str_replace($pid, '', $raw));
                }
                else{
                    $pid = '';
                }
            }

            $this->parsedCitations[] = [ "pid" => $pid, "raw" => $raw ];
        }

        $this->addLog(var_export($this->parsedCitations, true));

    }

    /**
     * Clean and normalize string
     *
     * @param $citationRawLine
     * @return string
     */
    private function cleanCitationString($citationRawLine): string
    {

        // Strip whitespace
        $citationRawLine = trim($citationRawLine);

        // Strip slashes
        $citationRawLine = stripslashes($citationRawLine);

        // Remove trailing/leading line breaks.
        $citationRawLine = trim($citationRawLine, "\n");

        // Normalize whitespace
        $citationRawLine = preg_replace('/[\s]+/', ' ', $citationRawLine);

        return $citationRawLine;

    }

    /**
     * Remove numbers from the beginning of each citation.
     *
     * @param $citationRawLine
     * @return string
     */
    private function removeLeadingNumbersFromBeginning($citationRawLine): string
    {

        $citationRawLine = preg_replace(
            '/^\s*[\[#]?[0-9]+[.)\]]?\s*/',
            '',
            $citationRawLine);

        return $citationRawLine;

    }

    /* debug */
    public $debug = '____S____' . "\r\n";
    function addLog($str){ $this->debug .= $str . "\r\n"; }
    function getLog(){ return $this->debug . "____E____"; }

}
