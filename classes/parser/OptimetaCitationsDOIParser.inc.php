<?php
/**
 * @file plugins/generic/optimetaCitations/classes/parser/OptimetaCitationsDOIParser.inc.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class OptimetaCitationsDOIParser
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Class for parsing citations
 */

import('plugins.generic.optimetaCitations.classes.model.OptimetaCitationsCitationModel');

class OptimetaCitationsDOIParser
{
	/**
	 * Regex to extract DOI
	 *
	 * @var string
	 */
	private $regex = '(10[.][0-9]{4,}[^\s"/<>]*/[^\s"<>]+)';

    /**
     * @param $raw
     * @return OptimetaCitationsCitationModel
     */
	public function getParsed($raw): OptimetaCitationsCitationModel
	{
		$doi = '';
		$rawRemainder = $raw;
		$doiArray = [];
		preg_match($this->regex, $rawRemainder, $doiArray);

		if (!empty($doiArray[0])) {
			$doi = $doiArray[0];
		}

		if (!empty($doi)) {
			$pidCorrect = 'https://doi.org/' . $doi;

			$prefix = [
				'http://doi.org/' . $doi,
				'http://www.doi.org/' . $doi,
				'https://www.doi.org/' . $doi,
				'http://doi:' . $doi,
				'https://doi:' . $doi,
				'doi:' . $doi,
				'doi: ' . $doi
			];

			$rawRemainder = str_replace($prefix, $pidCorrect, $rawRemainder);
			$rawRemainder = str_replace($pidCorrect, '', $rawRemainder);
			$rawRemainder = str_replace($doi, '', $rawRemainder);

			$doi = trim($pidCorrect, '.');
		}

        $model = new OptimetaCitationsCitationModel();
        $model->doi = $doi;
        $model->rawRemainder = $rawRemainder;

        return $model;
	}
}
