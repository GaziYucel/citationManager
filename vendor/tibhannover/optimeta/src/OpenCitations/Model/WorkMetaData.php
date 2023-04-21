<?php
/**
 * @file plugins/generic/optimetaCitations/vendor/tibhannover/optimeta/src/OpenCitations/Model/WorkMetaData.php
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

namespace Optimeta\Shared\OpenCitations\Model;

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