<?php
/**
 * @file classes/PID/Url.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Url
 * @brief Url class
 */

namespace APP\plugins\generic\citationManager\classes\PID;

class Url extends AbstractPid
{
    /** @copydoc AbstractPid::regex */
    public const regex = '%\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))%s';
}
