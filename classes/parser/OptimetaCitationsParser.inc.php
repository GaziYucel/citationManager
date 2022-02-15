<?php
/**
 * @file plugins/generic/optimetaCitations/classes/parser/OptimetaCitationsParser.inc.php
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

import('plugins.generic.optimetaCitations.classes.model.OptimetaCitationsCitationModel');
import('plugins.generic.optimetaCitations.classes.parser.OptimetaCitationsDOIParser');
import('plugins.generic.optimetaCitations.classes.parser.OptimetaCitationsURLParser');

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
	 * Constructor.
	 * @param $rawCitations string an unparsed citation string
	 */
	function __construct(string $citationsRaw = "")
	{
		$this->citationsRaw = $citationsRaw;
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
            $objRowParsed = new OptimetaCitationsCitationModel();

            // doi parser
            $doiParser = new OptimetaCitationsDOIParser();
            $objDoi = $doiParser->getParsed($rowRaw); // OptimetaCitationsCitationModel
            $objRowParsed->doi = $objDoi->doi;
            $objRowParsed->rawRemainder = $this->cleanCitationString($objDoi->rawRemainder);

            // url parser (after parsing doi)
            $urlParser = new OptimetaCitationsURLParser();
            $objUrl = $urlParser->getParsed($objRowParsed->rawRemainder); // OptimetaCitationsCitationModel
            $objRowParsed->url = $objUrl->url;
            $objRowParsed->rawRemainder = $this->cleanCitationString($objUrl->rawRemainder);

            $objRowParsed->raw = $rowRaw;

            // push to citations parsed array
			$this->citationsParsed[] = (array) $objRowParsed;
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
		// strip whitespace
		$text = trim($text);

		// strip .
		$text = trim($text, '.');

		// strip ,
		$text = trim($text, ',');

		// strip slashes
		$text = stripslashes($text);

		// normalize whitespace
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
