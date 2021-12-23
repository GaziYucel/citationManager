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
	private $rawCitations = "";

	/**
	 * Array which hold the parsed citations: ( ( "pid" => "pid1", "raw" => "raw1" ), ... )
	 *
	 * @var array
	 */
	private $parsedCitations = [];

	/**
	 * Constructor.
	 * @param $rawCitation string an unparsed citation string
	 */
	function __construct(string $rawCitations = "")
	{
		$this->rawCitations= $rawCitations;
    }

	/**
	 * Returns parsed citations as an array
	 *
	 * @return parsedCitations array [ (doi1, citation1), (doi2, citations2), ... ]
	 */
	public function getParsedCitationsArray()
	{
		$this->parse();
		return $this->parsedCitations;
	}

    /**
     * Returns parsed citations as an JSON
     *
     * @return parsedCitations json
     */
    public function getParsedCitationsJson()
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
    private function parse()
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
                $raw = str_replace('http://doi.org/' . $pid, '', $raw);
                $raw = str_replace('https://doi.org/' . $pid, '', $raw);
                $raw = trim($raw);
                $pid = 'https://doi.org/' . $pid;
            }

            $this->parsedCitations[] = [ "pid" => $pid, "raw" => $raw ];
        }

    }

    /**
     * Clean and normalize string
     *
     * @param $citationRawLine
     * @return string
     */
    private function cleanCitationString($citationRawLine){

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
    private function removeLeadingNumbersFromBeginning($citationRawLine){

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
