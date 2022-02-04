<?php

/**
 * @file plugins/generic/optimetaCitations/classes/OptimetaCitationsDataModel.inc.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @package plugins.generic.OptimetaCitations
 * @class OptimetaCitationsDataModel
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Class Citations Data Model
 */

class OptimetaCitationsDataModel
{
    public $author;
    public $orcid;
    public $title;
    public $doi;
    public $urn;
    public $date;
    public $volume;
    public $issue;
    public $pages;
    public $subject;
    public $fulltext;
    public $rawRemainder;
    public $raw;

    public static $entities = [
        "author" => "",
        "orcid" => "",
        "title" => "",
        "doi" => "",
        "urn" => "",
        "date" => "",
        "volume" => "",
        "issue" => "",
        "pages" => "",
        "subject" => "",
        "fulltext" => "",
        "rawRemainder" => "",
        "raw" => ""
    ];
}
