<?php
/**
 * @file classes/PID/OpenAlex.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class OpenAlex
 * @brief OpenAlex class
 */

namespace APP\plugins\generic\citationManager\classes\PID;

class OpenAlex extends AbstractPid
{
    /** @copydoc AbstractPid::regex */
    public const prefix = 'https://openalex.org';

    /** @copydoc AbstractPid::prefixInCorrect */
    public const prefixInCorrect = [
        'openalex:',
        'openalex.org/works',
        'www.openalex.org/works'
    ];
}
