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
    private $doiUrl = 'doi.org';

    /**
     * Regex to extract DOI
     *
     * @var string
     */
    // '/^10.\d{4,9}/[-._;()/:A-Z0-9]+$/i'; // crossref regex, doesn't work
    private $regexDoi = '(10[.][0-9]{4,}[^\s"/<>]*/[^\s"<>]+)';

    /**
     * Variable which will hold the raw citations
     *
     * @var string
     */
    private $citationsRaw = "";

    /**
     * Array which hold the parsed citations: [ [ "pid" => "pid1", "raw" => "raw1" ], ... ]
     *
     * @var array
     */
    private $citationsParsed = [];

    /**
     * Constructor.
     * @param $rawCitations string an unparsed citation string
     */
    function __construct(string $citationsRaw = "")
    {
        $this->citationsRaw= $citationsRaw;
    }

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

		if(sizeof($this->citationsParsed) == 0){ return '[]'; }

        return json_encode($this->citationsParsed);
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
        $citationsRaw = $this->citationsRaw;
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

            $this->citationsParsed[] = [ "pid" => $pid, "raw" => $raw ];
        }

        $this->addLog(var_export($this->citationsParsed, true));

    }

    /**
     * Clean and normalize string
     *
     * @param $citationsRawLine
     * @return string
     */
    private function cleanCitationString($citationsRawLine): string
    {

        // Strip whitespace
        $citationsRawLine = trim($citationsRawLine);

        // Strip slashes
        $citationsRawLine = stripslashes($citationsRawLine);

        // Remove trailing/leading line breaks.
        $citationsRawLine = trim($citationsRawLine, "\n");

        // Normalize whitespace
        $citationsRawLine = preg_replace('/[\s]+/', ' ', $citationsRawLine);

        return $citationsRawLine;

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
