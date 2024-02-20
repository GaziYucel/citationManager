<?php
/**
 * @file classes/External/OpenCitations/DataModels/WorkMetaData.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class WorkMetaData
 * @brief MetaData of citing and cited works.
 */

namespace APP\plugins\generic\citationManager\classes\External\OpenCitations\DataModels;

class WorkMetaData
{
    /** @var string|null */
    public ?string $id = null;
    
    /** @var string|null */
    public ?string $title = null;
    
    /** @var string|null */
    public ?string $author = null;
    
    /** @var string|null */
    public ?string $pub_date = null;
    
    /** @var string|null */
    public ?string $venue = null;
    
    /** @var string|null */
    public ?string $volume = null;
    
    /** @var string|null */
    public ?string $issue = null;
    
    /** @var string|null */
    public ?string $page = null;
    
    /** @var string|null */
    public ?string $type = null;
    
    /** @var string|null */
    public ?string $publisher = null;

    /** @var string|null */
    public ?string $editor = null;
}
