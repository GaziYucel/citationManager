<?php
/**
 * @file plugins/generic/optimetaCitations/classes/PID/OpenAlex.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class OpenAlex
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief OpenAlex class
 */

namespace APP\plugins\generic\optimetaCitations\classes\PID;

class OpenAlex
{
    /**
     * Correct prefix
     *
     * @var string
     */
    public string $prefix = 'https://openalex.org';

    /**
     * Incorrect prefixes
     *
     * @var array|string[]
     */
    public array $prefixInCorrect = [
        'http://openalex.org/works',
        'http://www.openalex.org/works',
        'https://www.openalex.org/works'
    ];

    /**
     * Add prefix to PID
     *
     * @param string|null $pid
     * @return string
     */
    public function addPrefixToPid(?string $pid): string
    {
        if (empty($pid)) return '';

        return $this->prefix . '/' . trim($pid, ' /');
    }

    /**
     * Remove prefix from URL
     *
     * @param string|null $url
     * @return string
     */
    public function removePrefixFromUrl(?string $url): string
    {
        if (empty($url)) return '';

        return trim(str_replace($this->prefix, '', $url), ' ./');
    }

    /**
     * Normalize PID in a string by removing any (in)correct prefixes, e.g.
     * http://... > https:....
     *
     * @param string $string
     * @param string $pid 10.12345/tib.11.2.3, Q12345678, w1234567890
     * @return string
     */
    public function normalize(string $string, string $pid): string
    {
        if (empty($string) || empty($pid)) return '';

        $doiListToRemove = [];

        foreach ($this->prefixInCorrect as $key) {
            $doiListToRemove[] = $key . '/' . $pid;
        }

        return trim(str_replace($doiListToRemove, $this->addPrefixToPid($pid), $string));
    }
}