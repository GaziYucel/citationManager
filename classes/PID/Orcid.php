<?php
/**
 * @file classes/PID/Orcid.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Orcid
 * @brief Orcid class
 */

namespace APP\plugins\generic\citationManager\classes\PID;

class Orcid extends AbstractPid
{
    /** @copydoc AbstractPid::regex */
    public const prefix = 'https://orcid.org';

    /** @copydoc AbstractPid::prefixInCorrect */
    public const prefixInCorrect = [
        'orcid:',
        'orcid_id:',
        'orcidId:'
    ];
}
