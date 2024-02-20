<?php
/**
 * @file classes/PID/Ror.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Ror
 * @brief Ror class
 */

namespace APP\plugins\generic\citationManager\classes\PID;

class Ror extends AbstractPid
{
    /** @copydoc AbstractPid::regex */
    public const regex = '/\[[\s]*https:\/\/ror\.org\/[\w|\d]*[\s]*\]/';

    /** @copydoc AbstractPid::prefix */
    public const prefix = 'https://ror.org';

    /** @copydoc AbstractPid::prefixInCorrect */
    public const prefixInCorrect = [
        'ror:'
    ];
}
