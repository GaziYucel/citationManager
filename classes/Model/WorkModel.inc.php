<?php
/**
 * @desc Works are scholarly documents like journal articles, books, datasets, and theses.
 */
namespace Optimeta\Citations\Model;

class WorkModel
{
    /**
     * @var string
     * @desc The DOI for the work.
     * @see
     * @example https://doi.org/10.7717/peerj.4375
     */
    public $doi;

    /**
     * @var string
     * @desc The URL for the work.
     * @see
     * @example https://opencitations.github.io/ontology/current/ontology.html
     */
    public $url;

    /**
     * @var string
     * @desc The URN for the work.
     * @see
     * @example urn:nbn:de:101:1-2019072802401757702913
     */
    public $urn;

    /**
     * @var string
     * @desc The title of this work.
     * @see
     * @example "The state of OA: a large-scale analysis of the prevalence and impact of Open Access articles"
     */
    public $title;

    /**
     * @var string
     * @desc The abstract of this work.
     * @see
     * @example "The state of OA: a large-scale analysis of the prevalence and impact of Open Access articles ..."
     */
    public $abstract;

    /**
     * @var integer
     * @desc The year this work was published.
     * @see
     * @example 2018
     */
    public $publication_year;

    /**
     * @var string
     * @desc The day when this work was published, formatted as an ISO 8601 date.
     * @see https://en.wikipedia.org/wiki/ISO_8601
     * @example "2018-02-13"
     */
    public $publication_date;

    /**
     * @var string
     * @desc The type or genre of the work.
     * @see https://api.crossref.org/types
     * @example "journal-article"
     */
    public $type;

    /**
     * @var object[]
     * @desc List of Author objects, each representing an author.
     * @see Optimeta\Citations\Model\AuthorModel
     * @example [ AuthorModel[], AuthorModel[] ]
     */
    public $authors;

    /**
     * @var integer
     * @desc The number of citations to this work. These are the times that other works have cited this work: Other works > This work
     * @see
     * @example cited_by_count: 382
     */
    public $cited_by_count;

    /**
     * @var string
     * @desc The volume of issue of journal
     * @see
     * @example 495
     */
    public $volume;

    /**
     * @var string
     * @desc The issue of journal
     * @see
     * @example 7442
     */
    public $issue;

    /**
     * @var string
     * @desc The number of pages of the work/article
     * @see
     * @example 4
     */
    public $pages;

    /**
     * @var string
     * @desc The number of pages of the work/article
     * @see
     * @example 49
     */
    public $first_page;

    /**
     * @var string
     * @desc The number of pages of the work/article
     * @see
     * @example 59
     */
    public $last_page;

    /**
     * @var boolean
     * @desc True if we know this work has been retracted. False if we don't know or not retracted.
     * @see
     * @example false
     */
    public $is_retracted;

    /**
     * @var string
     * @desc The ISSN-L identifying this venue. This is the Canonical External ID for venues.
     * @see https://docs.openalex.org/about-the-data#canonical-external-ids
     * @example 2167-8359
     */
    public $venue_issn_l;

    /**
     * @var string
     * @desc The name of the venue.
     * @see
     * @example PeerJ
     */
    public $venue_name;

    /**
     * @var string
     * @desc The name of this venue's publisher. Publisher is a tricky category, as journals often change publishers, publishers merge, publishers have subsidiaries ("imprints"), and of course no one is consistent in their naming. In the future, we plan to roll out support for a more structured publisher field, but for now it's just a string.
     * @see
     * @example Peerj
     */
    public $venue_publisher;

    /**
     * @var boolean
     * @desc
     * @see
     * @example true
     */
    public $venue_is_oa;

    /**
     * @var string
     * @desc The OpenAlex ID for this venue
     * @see https://docs.openalex.org/about-the-data#the-openalex-id
     * @example V1983995261
     */
    public $venue_openalex_id;

    /**
     * @var string
     * @desc The URL of the venue
     * @see
     * @example http://www.peerj.com/
     */
    public $venue_url;

    /**
     * @var string
     * @desc The last time anything in this Work object changed, expressed as an ISO 8601 date string. This date is updated for any change at all, including increases in various counts.
     * @see https://en.wikipedia.org/wiki/ISO_8601
     * @example updated_date: "2022-01-02T00:22:35.180390"
     */
    public $updated_date;

    /**
     * @var string
     * @desc The date this Work object was created in the OpenAlex dataset, expressed as an ISO 8601 date string.
     * @see https://en.wikipedia.org/wiki/ISO_8601
     * @example created_date: "2017-08-08"
     */
    public $created_date;

    /**
     * @var string
     * @desc The OpenAlex ID for this work
     * @see https://docs.openalex.org/about-the-data#the-openalex-id
     * @example W2741809807
     */
    public $openalex_id;

    /**
     * @var string
     * @desc The Wikidata QID for this work
     * @see https://www.wikidata.org/wiki/Q43649390
     * @example Q43649390
     */
    public $wikidata_qid;

    /**
     * @var string
     * @desc The OpenCitations ID (OCI) for this work
     * @see https://opencitations.net/index/api/v1#/citations/{doi}
     * @example 0200100000236102818370204030309-020070701073625141427193701090900
     */
    public $opencitations_id;

    /**
     * @var boolean
     * @desc Is this citation processed or to be processed
     * @see
     * @example false
     */
    public $isProcessed;

    /**
     * @var string
     * @desc The unchanged raw citation
     * @see
     * @example
     */
    public $raw;
}
