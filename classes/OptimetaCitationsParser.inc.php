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
	private string $regexDoi = "/^10.\d{4,9}/[-._;()/:A-Z0-9]+$/i";

	/**
	 * Regex to extract URL's
	 *
	 * @var string
	 */
	private string $regexUrl = "";

	/**
	 * Variable which will hold the raw citations
	 *
	 * @var string
	 */
	private string $citationsRaw = "";

	/**
	 * Array which hold the parsed citations: i > pid, restRaw
	 *
	 * @var array
	 */
	private array $parsedCitationsArray = [];

	function __construct(string $citationsRaw = "")
	{
		$this->citationsRaw= $citationsRaw;
    }

	/**
	 * Function which will do the actual parsing and return parsed citations as an array
	 *
	 * @return parsedCitationsArray
	 */
	public function getParsedCitations() {
		if(strlen($this->citationsRaw) > 0){



			return $this->parsedCitationsArray;
		}
		return null;
	}
}
