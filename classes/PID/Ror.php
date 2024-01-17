<?php
/**
 * @file plugins/generic/optimetaCitations/classes/PID/Ror.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Ror
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Ror class
 */

namespace APP\plugins\generic\optimetaCitations\classes\PID;

class Ror
{
    /**
     * Regex to extract URL
     * @var string
     */
    public string $regex = '/\[[\s]*https:\/\/ror\.org\/[\w|\d]*[\s]*\]/';

    /**
     * Correct prefix
     * @var string
     */
    public string $prefix = 'https://ror.org/';

    /**
     * Incorrect prefixes
     * @var array|string[]
     */
    public array $prefixInCorrect = [
        'http://ror.org/'
    ];

    /**
     * Remove prefix from URL
     * @param string|null $url
     * @return string
     */
    public function removePrefixFromUrl(?string $url): string
    {
        if (empty($url)) {
            return '';
        }

        return str_replace($this->prefix, '', $url);
    }

    /**
     * Add prefix to URL
     * @param string|null $url
     * @return string
     */
    public function addPrefixToUrl(?string $url): string
    {
        if (empty($url)) {
            return '';
        }

        return $url . $this->prefix;
    }
}
