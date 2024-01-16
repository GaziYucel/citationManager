<?php
/**
 * @file plugins/generic/optimetaCitations/classes/Model/WorkModel.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class WorkModel
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Works are scholarly documents like journal articles, books, datasets, and theses.
 */

namespace APP\plugins\generic\optimetaCitations\classes\Model;

class WorkModel
{
    /**
     * The DOI for the work.
     * 
     * @var string
     * @see
     * @example https://doi.org/10.7717/peerj.4375
     */
    public string $doi;

    /**
     * The URL for the work.
     * @var string
     * @see
     * @example https://opencitations.github.io/ontology/current/ontology.html
     */
    public string $url;

    /**
     * The URN for the work.
     * 
     * @var string
     * @see
     * @example urn:nbn:de:101:1-2019072802401757702913
     */
    public string $urn;

    /**
     * The title of this work.
     * 
     * @var string
     * @see
     * @example "The state of OA: a large-scale analysis of the prevalence and impact of Open Access articles"
     */
    public string $title;

    /**
     * The abstract of this work.
     * 
     * @var string
     * @see
     * @example "The state of OA: a large-scale analysis of the prevalence and impact of Open Access articles ..."
     */
    public string $abstract;

    /**
     * The year this work was published.
     * 
     * @var int
     * @see
     * @example 2018
     */
    public int $publication_year;

    /**
     * The day when this work was published, formatted as an ISO 8601 date.
     * 
     * @var string
     * @see https://en.wikipedia.org/wiki/ISO_8601
     * @example "2018-02-13"
     */
    public string $publication_date;

    /**
     * The type or genre of the work.
     * 
     * @var string
     * @see https://api.crossref.org/types
     * @example "journal-article"
     */
    public string $type;

    /**
     * List of Author objects, each representing an author.
     * 
     * @var array[object]
     * @see AuthorModel
     * @example [ AuthorModel[], AuthorModel[] ]
     */
    public array $authors;

    /**
     * The number of citations to this work. These are the times that other works have cited this work: Other works > This work
     * @var int
     * @see
     * @example cited_by_count: 382
     */
    public int $cited_by_count;

    /**
     * The volume of issue of journal
     *
     * @var string
     * @see
     * @example 495
     */
    public string $volume;

    /**
     * The issue of journal
     *
     * @var string
     * @see
     * @example 7442
     */
    public string $issue;

    /**
     * The number of pages of the work/article
     *
     * @var string
     * @see
     * @example 4
     */
    public string $pages;

    /**
     * The number of pages of the work/article
     *
     * @var string
     * @see
     * @example 49
     */
    public string $first_page;

    /**
     * The number of pages of the work/article
     *
     * @var string
     * @see
     * @example 59
     */
    public string $last_page;

    /**
     * True if we know this work has been retracted. False if we don't know or not retracted.
     *
     * @var bool
     * @see
     * @example false
     */
    public bool $is_retracted;

    /**
     * The ISSN-L identifying this venue. This is the Canonical External ID for venues.
     *
     * @var string
     * @see https://docs.openalex.org/about-the-data#canonical-external-ids
     * @example 2167-8359
     */
    public string $venue_issn_l;

    /**
     * The name of the venue.
     *
     * @var string
     * @see
     * @example PeerJ
     */
    public string $venue_name;

    /**
     * The name of this venue's publisher. Publisher is a tricky category, as journals often change publishers,
     * publishers merge, publishers have subsidiaries ("imprints"), and of course no one is consistent in their naming.
     * In the future, we plan to roll out support for a more structured publisher field, but for now it's just a string.
     *
     * @var string
     * @see
     * @example Peerj
     */
    public string $venue_publisher;

    /**
     * Is this venue open access
     *
     * @var bool
     * @see
     * @example true
     */
    public bool $venue_is_oa;

    /**
     * The OpenAlex ID for this venue
     *
     * @var string
     * @see https://docs.openalex.org/about-the-data#the-openalex-id
     * @example V1983995261
     */
    public string $venue_openalex_id;

    /**
     * The URL of the venue
     *
     * @var string
     * @see
     * @example http://www.peerj.com/
     */
    public string $venue_url;

    /**
     * The last time anything in this Work object changed, expressed as an ISO 8601 date string.
     * This date is updated for any change at all, including increases in various counts.
     *
     * @var string
     * @see https://en.wikipedia.org/wiki/ISO_8601
     * @example updated_date: "2022-01-02T00:22:35.180390"
     */
    public string $updated_date;

    /**
     * The date this Work object was created in the OpenAlex dataset, expressed as an ISO 8601 date string.
     *
     * @var string
     * @see https://en.wikipedia.org/wiki/ISO_8601
     * @example created_date: "2017-08-08"
     */
    public string $created_date;

    /**
     * The OpenAlex ID for this work
     *
     * @var string
     * @see https://docs.openalex.org/about-the-data#the-openalex-id
     * @example W2741809807
     */
    public string $openalex_id;

    /**
     * The OpenAlex URL for this work
     *
     * @var string
     * @see https://docs.openalex.org/about-the-data#the-openalex-id
     * @example https://docs.openalex.org/about-the-data#W2741809807
     */
    public string $openalex_url;

    /**
     * The Wikidata QID for this work
     *
     * @var string
     * @see https://www.wikidata.org/wiki/Q43649390
     * @example Q43649390
     */
    public string $wikidata_qid;

    /**
     * The Wikidata URL for this work
     *
     * @var string
     * @see https://www.wikidata.org/wiki/Q43649390
     * @example https://www.wikidata.org/wiki/Q43649390
     */
    public string $wikidata_url;

    /**
     * The OpenCitations URL for this work
     *
     * @var string
     * @see https://opencitations.net/index/api/v1#/citations/{oci}
     * @example 0200100000236102818370204030309-020070701073625141427193701090900
     */
    public string $opencitations_id;

    /**
     * The OpenCitations URL for this work
     *
     * @var string
     * @see https://opencitations.net/index/api/v1#/citations/{oci}
     * @example https://opencitations.net/index/api/v1#/citations/0200100000236102818370204030309-020070701073625141427193701090900
     */
    public string $opencitations_url;

    /**
     * Is this work processed or to be processed
     *
     * @var bool
     * @see
     * @example false
     */
    public bool $isProcessed;
}
