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
	private string $regexDoi = '/^10.\d{4,9}/[-._;()/:A-Z0-9]+$/i';

	/**
	 * Regex to extract URL's
	 *
	 * @var string
	 */
	private string $regexUri = '#(http|https|ftp)://[\d\w\.-]+\.[\w\.]{2,6}[^\s\]\[\<\>]*/?#';

	/**
	 * Variable which will hold the raw citations
	 *
	 * @var string
	 */
	private string $rawCitations = "";

	/**
	 * Array which hold the parsed citations: [ (doi1, citation1), (doi2, citations2), ... ]
	 *
	 * @var array
	 */
	private array $parsedCitations = [];

	/**
	 * Constructor.
	 * @param $rawCitation string an unparsed citation string
	 */
	function __construct(string $rawCitations = "")
	{
		$this->rawCitations= $rawCitations;
    }

	/**
	 * Function returns parsed citations as an array
	 *
	 * @return parsedCitations array [ (doi1, citation1), (doi2, citations2), ... ]
	 */
	public function getParsedCitations()
	{
		$this->parseDOIs();
		return $this->parsedCitations;
	}

	/**
	 * Function which will do the actual parsing and return parsed citations as an array
	 *
	 * @param $input string
	 * @return void
	 */
	private function parseDOIs()
	{
		$input = $this->rawCitations;
		$citations = []; // ( citation1, citation2, ... )

		// Assumed that raw citations are separated with line endings

		// Strip slashes and whitespace
		$input = trim(stripslashes($input));

		// Normalize whitespace
		$input = PKPString::regexp_replace('/[\s]+/', ' ', $input);

		// Remove empty lines and normalize line endings.
		$input = PKPString::regexp_replace('/[\r\n]+/s', "\n", $input);

		// Remove trailing/leading line breaks.
		$input = trim($input, "\n");
		// Return empty array if input is empty
		if(empty($input))
		{
			return [];
		}

		// Break up at line endings.
		$citations = explode("\n", $input);

		// Remove numbers from the beginning of each citation
		foreach($citations as $index => $citation) {
			$citation = trim($citation);
			$citations[$index] = PKPString::regexp_replace('/^\s*[\[#]?[0-9]+[.)\]]?\s*/', '', $citation);
		}

		// extract doi's from every citation
		foreach($citations as $index => $citation) {
			// match doi in citation
			preg_match($this->regexDoi, $citation, $doi);
			if(!empty($doi[1]))
			{
				$this->parsedCitations[] = [ $doi[1], str_replace($doi[1], '', $citation) ];
			}
			else
			{
				$parsedCitations[] = [ "", $citation ];
			}
		}
	}

}
