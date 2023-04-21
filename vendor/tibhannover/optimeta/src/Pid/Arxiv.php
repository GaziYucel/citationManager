<?php
/**
 * @file plugins/generic/optimetaCitations/vendor/tibhannover/optimeta/src/Pid/Arxiv.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Arxiv
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Arxiv class
 */

namespace Optimeta\Shared\Pid;

class Arxiv
{
    /**
     * @desc Correct prefix
     * @var string
     */
    public string $prefix = 'https://arxiv.org/abs/';

    /**
     * @desc Incorrect prefixes
     * @var array|string[]
     */
    public array $prefixInCorrect = [
        'http://arxiv.org/abs/'
    ];

    /**
     * @desc Remove $prefix from URL
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
