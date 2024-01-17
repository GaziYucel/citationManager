<?php
/**
 * @file plugins/generic/optimetaCitations/classes/PID/Urn.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Urn
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Urn class
 */

namespace APP\plugins\generic\optimetaCitations\classes\PID;

class Urn
{
    /**
     * Regex to extract URN
     * @var string
     */
    public string $regex = '/urn:([a-z0-9][a-z0-9-]{1,31}):((?:[-a-z0-9()+,.:=@;$_!*\'&~\/]|%[0-9a-f]{2})+)(?:(\?\+)((?:(?!\?=)(?:[-a-z0-9()+,.:=@;$_!*\'&~\/\?]|%[0-9a-f]{2}))*))?(?:(\?=)((?:(?!#).)*))?(?:(#)((?:[-a-z0-9()+,.:=@;$_!*\'&~\/\?]|%[0-9a-f]{2})*))?$/i';

    /**
     * @param $raw
     * @return string|null
     */
    public function getUrnParsed($raw): ?string
    {
        $match = '';
        $matches = [];

        preg_match($this->regex, $raw, $matches);

        if (!empty($matches[0])) $match = $matches[0];

        if (empty($match)) return null;

        return trim($match, '.');
    }
}