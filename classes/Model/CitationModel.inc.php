<?php
namespace Optimeta\Citations\Model;

use Stringy\StaticStringy;

class CitationModel
{
    /**
     * @var String
     * @desc The DOI for the work.
     * @see
     * @example https://doi.org/10.7717/peerj.4375
     */
    public $doi;

    /**
     * @var String
     * @desc The URL for the work.
     * @see
     * @example https://opencitations.github.io/ontology/current/ontology.html
     */
    public $url;

    /**
     * @var String
     * @desc The URN for the work.
     * @see
     * @example urn:nbn:de:101:1-2019072802401757702913
     */
    public $urn;

    /**
     * @var String
     * @desc The title of this work.
     * @see
     * @example "The state of OA: a large-scale analysis of the prevalence and impact of Open Access articles"
     */
    public $title;

    /**
     * @var Integer
     * @desc The year this work was published.
     * @see
     * @example 2018
     */
     public $publication_year;

    /**
     * @var String
     * @desc The day when this work was published, formatted as an ISO 8601 date.
     * @see https://en.wikipedia.org/wiki/ISO_8601
     * @example "2018-02-13"
     */
     public $publication_date;

    /**
     * @var String
     * @desc The type or genre of the work.
     * @see https://api.crossref.org/types
     * @example "journal-article"
     */
     public $type;

    /**
     * @var Object[]
     * @desc List of Authors objects, each representing an author.
     * @see Optimeta\Citations\Model\AuthorModel
     * @example [ AuthorModel[], AuthorModel[] ]
     */
    public $authors;

    /**
     * @var Integer
     * @desc The number of citations to this work. These are the times that other works have cited this work: Other works > This work
     * @see
     * @example cited_by_count: 382
     */
     public $cited_by_count;

    /**
     * @var String
     * @desc The volume of issue of journal
     * @see
     * @example 495
     */
    public $volume;

    /**
     * @var String
     * @desc The issue of journal
     * @see
     * @example 7442
     */
    public $issue;

    /**
     * @var String
     * @desc The number of pages of the work/article
     * @see
     * @example 4
     */
    public $pages;

    /**
     * @var Boolean
     * @desc True if we know this work has been retracted. False if we don't know or not retracted.
     * @see
     * @example false
     */
     public $is_retracted;

    /**
     * @var String
     * @desc The last time anything in this Work object changed, expressed as an ISO 8601 date string. This date is updated for any change at all, including increases in various counts.
     * @see https://en.wikipedia.org/wiki/ISO_8601
     * @example updated_date: "2022-01-02T00:22:35.180390"
     */
     public $updated_date;

    /**
     * @var String
     * @desc The date this Work object was created in the OpenAlex dataset, expressed as an ISO 8601 date string.
     * @see https://en.wikipedia.org/wiki/ISO_8601
     * @example created_date: "2017-08-08"
     */
     public $created_date;

    /**
     * @var String
     * @desc The Wikidata QID for this work
     * @see https://www.wikidata.org/wiki/Q43649390
     * @example Q43649390
     */
    public $wikidata_qid;

    /**
     * @var String
     * @desc The OpenAlex ID for this work
     * @see https://docs.openalex.org/about-the-data#the-openalex-id
     * @example W2741809807
     */
    public $openalex_id;

    /**
     * @var String
     * @desc The remainder of raw citation after parsing
     * @see
     * @example
     */
    public $rawRemainder;

    /**
     * @var String
     * @desc The unchanged raw citation
     * @see
     * @example
     */
    public $raw;
}
