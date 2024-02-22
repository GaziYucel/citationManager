<?php
/**
 * @file plugins/generic/citationManager/vendor/tibhannover/optimeta/src/Pid/Doi.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Doi
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Doi class
 */

namespace Optimeta\Shared\Pid;

class Doi
{
    /**
     * Regex to extract DOI
     * @var string
     */
    public string $regex = '(10[.][0-9]{4,}[^\s"/<>]*/[^\s"<>]+)';

    /**
     * Correct prefix
     * @var string
     */
    public string $prefix = 'https://doi.org/';

    /**
     * Incorrect prefixes
     * @var array|string[]
     */
    public array $prefixInCorrect = [
        'http://dx.doi.org/',
        'http://doi.org/',
        'http://www.doi.org/',
        'https://www.doi.org/',
        'http://doi:',
        'https://doi:',
        'doi:',
        'doi: '];

    /**
     * Extract doi from string
     * @param $raw
     * @return string|null
     */
    public function getDoiParsed($raw): ?string
    {
        $match = '';
        $matches = [];

        preg_match($this->regex, $raw, $matches);

        if (!empty($matches[0])) $match = $matches[0];

        if (empty($match)) return null;

        $pidCorrect = $match;

        return trim($pidCorrect, '.');
    }

    /**
     * Normalize a DOI that is found in a raw citation by removing any (in)correct prefixes
     * @param $raw
     * @param $doi
     * @return string|null
     */
    public function normalizeDoi($raw, $doi): ?string
    {
        if (empty($raw)) return null;

        $doiListToRemove = [];

        $doiListToRemove[] = $this->prefix . $doi;

        foreach ($this->prefixInCorrect as $key) {
            $doiListToRemove[] = $key . $this->removePrefixFromUrl($doi);
        }

        return str_replace($doiListToRemove, $doi, $raw);
    }

    /**
     * Remove prefix from URL
     * @param ?string $url
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