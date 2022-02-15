<?php
/**
 * @file plugins/generic/optimetaCitations/classes/parser/OptimetaCitationsURLParser.inc.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class OptimetaCitationsURLParser
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Class for parsing citations
 */

import('plugins.generic.optimetaCitations.classes.model.OptimetaCitationsCitationModel');

class OptimetaCitationsURLParser
{
	/**
	 * Regex to extract URL
	 *
	 * @var string
	 */
    private $regex = '%\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))%s';

    /**
     * @param $raw
     * @return OptimetaCitationsCitationModel
     */
	public function getParsed($raw): OptimetaCitationsCitationModel
	{
		$url = '';
		$rawRemainder = $raw;
		$urlArray = [];
		preg_match($this->regex, $rawRemainder, $urlArray);

		if (!empty($urlArray[0])) {
			$url = $urlArray[0];
		}

		if (!empty($url)) {
			$rawRemainder = str_replace($url, '', $rawRemainder);
			$url = trim($url, '.');
		}

        $model = new OptimetaCitationsCitationModel();
        $model->url = $url;
        $model->rawRemainder = $rawRemainder;

        return $model;
	}
}
