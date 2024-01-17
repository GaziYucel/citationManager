<?php
/**
 * @file plugins/generic/optimetaCitations/vendor/tibhannover/optimeta/src/OpenCitations/Model/WorkCitation.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class WorkCitation
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Relation Citing and Cited work.
 */

namespace Optimeta\Shared\OpenCitations\Model;

class WorkCitation
{
    public $citing_id;
    public $citing_publication_date;
    public $cited_id;
    public $cited_publication_date;
}