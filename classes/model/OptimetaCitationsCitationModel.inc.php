<?php
/**
 * @file plugins/generic/optimetaCitations/classes/model/OptimetaCitationsCitationModel.inc.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @package plugins.generic.OptimetaCitations
 * @class OptimetaCitationsCitationModel
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Class Citation Data Model
 */

class OptimetaCitationsCitationModel
{
    public $author;         // author of article
    public $orcid;          // orcid id of author
    public $title;          // title of article
    public $doi;            // doi of article
    public $urn;            // urn of article
    public $date;           // publication date
    public $volume;         // volume of issue of journal
    public $issue;          // issue of journal
    public $pages;          // number of pages of article
    public $subject;        // subject or category of article
    public $fulltext;       // link to fulltext
    public $rawRemainder;   // remainder of raw citation after parsing
    public $raw;            // unchanged raw citation
}
