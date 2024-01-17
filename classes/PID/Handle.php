<?php
/**
 * @file plugins/generic/optimetaCitations/classes/PID/Handle.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Handle
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Handle class
 */

namespace APP\plugins\generic\optimetaCitations\classes\PID;

class Handle
{
    /**
     * Correct prefix
     * @var string
     */
    public string $prefix = 'https://hdl.handle.net/';

    /**
     * Incorrect prefixes
     * @var array|string[]
     */
    public array $prefixInCorrect = [
        'http://hdl.handle.net/'
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
}
