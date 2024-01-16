<?php
/**
 * @file plugins/generic/optimetaCitations/classes/Pid/Orcid.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Orcid
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Orcid class
 */

namespace APP\plugins\generic\optimetaCitations\classes\Pid;

class Orcid
{
    /**
     * Correct prefix
     * @var string
     */
    public $prefix = 'https://orcid.org/';

    /**
     * Incorrect prefixes
     * @var array|string[]
     */
    public array $prefixInCorrect = [
        'http://orcid.org/'
    ];

    /**
     * Remove https://orcid.org/ from ORCID URL
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
     * Add $prefix to URL
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