<?php
/**
 * @file plugins/generic/optimetaCitations/classes/OpenCitations/Model/WorkMetaData.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class WorkMetaData
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief MetaData of citing and cited works.
 */

namespace APP\plugins\generic\optimetaCitations\classes\OpenCitations\Model;

class WorkMetaData
{
    public $id;
    public $title;
    public $author;
    public $pub_date;
    public $venue;
    public $volume;
    public $issue;
    public $page;
    public $type;
    public $publisher;
    public $editor;
}