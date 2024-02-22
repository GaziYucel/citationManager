<?php
/**
 * @file plugins/generic/citationManager/vendor/tibhannover/optimeta/src/Pid/Url.php
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

namespace Optimeta\Shared\Pid;

class Url
{
    /**
     * Regex to extract URL
     * @var string
     */
    public string $regex = '%\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))%s';

    /**
     * @param $raw
     * @return string|null
     */
    public function getUrlParsed($raw): ?string
    {
        $match = '';
        $matches = [];

        preg_match($this->regex, $raw, $matches);

        if (!empty($matches[0])) $match = $matches[0];

        if (empty($match)) return null;

        return trim($match, '.');
    }
}