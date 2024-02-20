<?php
/**
 * @file classes/PID/OpenCitations.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class OpenCitations
  * @brief OpenCitations class
 */

namespace APP\plugins\generic\citationManager\classes\PID;

class OpenCitations extends AbstractPid
{
    /** @copydoc AbstractPid::prefix */
    public const prefix = 'https://opencitations.net/oci';

    /** @copydoc AbstractPid::prefixInCorrect */
    public const prefixInCorrect = [
        'opencitations:'
    ];
}
