<?php
/**
 * @file classes/PID/Arxiv.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Arxiv
 * @brief Arxiv class
 */

namespace APP\plugins\generic\citationManager\classes\PID;

class Arxiv extends AbstractPid
{
    /** @copydoc AbstractPid::regex */
    public const regex = '%\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))%s';

    /** @copydoc AbstractPid::prefix */
    public const prefix = 'https://arxiv.org/abs';

    /** @copydoc AbstractPid::prefixInCorrect */
    public const prefixInCorrect = [
        'arxiv:'
    ];

    /** @copydoc AbstractPid::extractFromString() */
    public static function extractFromString(?string $string): string
    {
        $string = parent::extractFromString($string);

        $class = get_called_class();

        // check if prefix found in extracted string
        $prefixes = $class::prefixInCorrect;
        $prefixes[] = $class::prefix;

        foreach($prefixes as $prefix){
            if(str_contains($string, $prefix)) return $string;
        }

        return '';
    }
}
