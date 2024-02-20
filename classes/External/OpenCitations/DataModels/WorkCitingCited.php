<?php
/**
 * @file classes/External/OpenCitations/DataModels/WorkCitingCited.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class WorkCitingCited
 * @brief Relation Citing and Cited work.
 */

namespace APP\plugins\generic\citationManager\classes\External\OpenCitations\DataModels;

class WorkCitingCited
{
    /** @var string|null  */
    public ?string $citing_id = null;

    /** @var string|null */
    public ?string $citing_publication_date = null;

    /** @var string|null */
    public ?string $cited_id = null;

    /** @var string|null */
    public ?string $cited_publication_date = null;
}
