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
		foreach ($citationsArray as $index => $citationRawLine) {

			// Clean single citation
			$citationRawLine = $this->cleanCitationString($citationRawLine);

			// Remove numbers from the beginning of each citation
			$citationRawLine = $this->removeLeadingNumbersFromBeginning($citationRawLine);

			// match doi in citation
			$this->citationsParsed[] = $this->parseDoi($citationRawLine);
		}
	}

	private function parseDoi($citationRawLine): array
	{
		$pid = '';
		$raw = $citationRawLine;
		$doiArray = [];
		preg_match($this->regexDoi, $raw, $doiArray);

		if (!empty($doiArray[0])) {
			$pid = $doiArray[0];
		}

		if (!empty($pid)) {
			$pidCorrect = 'https://doi.org/' . $pid;

			$prefix = [
				'http://doi.org/' . $pid,
				'http://www.doi.org/' . $pid,
				'https://www.doi.org/' . $pid,
				'http://doi:' . $pid,
				'https://doi:' . $pid,
				'doi:' . $pid,
				'doi: ' . $pid
			];

			$raw = str_replace($prefix, $pidCorrect, $raw);
			$raw = str_replace($pidCorrect, '', $raw);
			$raw = str_replace($pid, '', $raw);
			$raw = $this->cleanCitationString($raw);

			$pid = trim($pidCorrect, '.');
		}

		return ["pid" => $pid, "raw" => $raw];
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

		// String .
		$citationsRawLine = trim($citationsRawLine, '.');

		// String ,
		$citationsRawLine = trim($citationsRawLine, ',');

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
}
