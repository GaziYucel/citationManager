<?php
/**
 * @file plugins/generic/optimetaCitations/classes/PID/Url.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Url
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Url class
 */

namespace APP\plugins\generic\optimetaCitations\classes\PID;

class Url
{
    /**
     * Regex to extract URL
     * @var string
     */
    public string $regex = '%\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))%s';

    /**
     * Extracts url from string, e.g. https://tib.eu/12345/abcde
     *
     * @param string $string
     * @return string
     */
    public function extractFromString(string $string): string
    {
        if (empty($string)) return '';

        $matches = [];

        preg_match($this->regex, $string, $matches);

        if (empty($matches[0])) return '';

        return trim($matches[0], ' ./');
    }
}