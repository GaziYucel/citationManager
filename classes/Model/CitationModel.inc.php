<?php
namespace Optimeta\Citations\Model;

class CitationModel
{
    public $author;         // author of article
    public $orcid;          // orcid id of author
    public $title;          // title of article
    public $doi;            // doi of article
    public $url;            // url of article
    public $date;           // publication date
    public $volume;         // volume of issue of journal
    public $issue;          // issue of journal
    public $pages;          // number of pages of article
    public $subject;        // subject or category of article
    public $fulltext;       // link to fulltext

    // internal
    public $rawRemainder;   // remainder of raw citation after parsing
    public $raw;            // unchanged raw citation
}
