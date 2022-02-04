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

import('plugins.generic.optimetaCitations.classes.OptimetaCitationsDataModel');
import('plugins.generic.optimetaCitations.classes.parser.OptimetaCitationsDOIParser');

class OptimetaCitationsParser
{
	/**
	 * Variable which will hold the raw citations
	 *
	 * @var string
	 */
	private $citationsRaw = "";

	/**
	 * Array which hold the parsed citations
	 *
	 * @var array
	 */
	private $citationsParsed = [];

    /**
     * Array which hold the citations data model
     *
     * @var array
     */
    private $citationRowParsedTemplate = [];

    /**
	 * Constructor.
	 * @param $rawCitations string an unparsed citation string
	 */
	function __construct(string $citationsRaw = "")
	{
		$this->citationsRaw = $citationsRaw;
        $this->citationRowParsedTemplate = OptimetaCitationsDataModel::$entities;
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

		if (sizeof($this->citationsParsed) == 0) {
			return '[]';
		}

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
		// Strip whitespace
		$citationsRaw = trim($this->citationsRaw);

		// Strip slashes
		$citationsRaw = stripslashes($citationsRaw);

		// Remove empty lines and normalize line endings.
		$citationsRaw = preg_replace('/[\r\n]+/s', "\n", $citationsRaw);

		// Remove trailing/leading line breaks.
		$citationsRaw = trim($citationsRaw, "\n");

		// Return if input is empty
		if (empty($citationsRaw)) {
			return;
		}

		// input not empty, continue parsing

		// Break up at line endings.
		$citationsArray = explode("\n", $citationsRaw);

		// Extract DOI from every citation
		foreach ($citationsArray as $index => $rowRaw) {

			// Clean single citation
			$rowRaw = $this->cleanCitationString($rowRaw);

			// Remove numbers from the beginning of each citation
			$rowRaw = $this->removeLeadingNumbersFromBeginning($rowRaw);

            // Get data model and fill empty rowParsed
            $rowParsed = $this->citationRowParsedTemplate;

            // fill with values
            $doiParser = new OptimetaCitationsDOIParser();
            $parsedDois = $doiParser->getParsedDoi($rowRaw); // OptimetaCitationsDataModel
            $rowParsed["doi"] = $parsedDois->doi;
            $rowParsed["rawRemainder"] = $this->cleanCitationString($parsedDois->rawRemainder);

            $rowParsed["raw"] = $rowRaw;

            // push to citations parsed array
			$this->citationsParsed[] = $rowParsed;
		}
	}

	/**
	 * Clean and normalize string
	 *
	 * @param $text
	 * @return string
	 */
	private function cleanCitationString($text): string
	{
		// Strip whitespace
		$text = trim($text);

		// String .
		$text = trim($text, '.');

		// String ,
		$text = trim($text, ',');

		// Strip slashes
		$text = stripslashes($text);

		// Remove trailing/leading line breaks.
		$text = trim($text, "\n");

		// Normalize whitespace
		$text = preg_replace('/[\s]+/', ' ', $text);

		return $text;
	}

	/**
	 * Remove numbers from the beginning of each citation.
	 *
	 * @param $text
	 * @return string
	 */
	private function removeLeadingNumbersFromBeginning($text): string
	{

		$text = preg_replace(
			'/^\s*[\[#]?[0-9]+[.)\]]?\s*/',
			'',
			$text);

		return $text;

	}
}
