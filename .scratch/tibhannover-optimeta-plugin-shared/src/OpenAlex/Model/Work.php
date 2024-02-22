<?php
/**
 * @file plugins/generic/citationManager/vendor/tibhannover/optimeta/src/OpenAlex/Model/Work.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Work
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Works are scholarly documents like journal articles, books, datasets, and theses.
 */

namespace Optimeta\Shared\OpenAlex\Model;

class Work
{
    /**
     * The OpenAlex ID for the work.
     * @var string
     * @see
     * @example id: "https://openalex.org/W2741809807"
     */
    public $id;

    /**
     * The DOI for the work.
     * @var string
     * @see
     * @example doi: "https://doi.org/10.7717/peerj.4375"
     */
    public $doi;

    /**
     * The title of this work.
     * @var string
     * @see
     * @example title: "The state of OA: a large-scale analysis of the prevalence and impact of Open Access articles",
     */
    public $title;

    /**
     * Exactly the same as Work.title. It's useful for Works to include a display_name property,
     * since all the other entities have one.
     * @var string
     * @see https://docs.openalex.org/about-the-data/work#title-1
     * @example display_name: "The state of OA: a large-scale analysis of the prevalence and impact of OA articles",
     */
    public $display_name;

    /**
     * The year this work was published.
     * @var integer
     * @see
     * @example publication_year: 2018
     */
    public $publication_year;

    /**
     * The day when this work was published, formatted as an ISO 8601 date.
     * @var string
     * @see https://en.wikipedia.org/wiki/ISO_8601
     * @example publication_date: "2018-02-13"
     */
    public $publication_date;

    /**
     * All the external identifiers that we know about for this work. IDs are expressed as URIs whenever possible.
     * @var object
     * @see
     * @example ids: {
     *              openalex: "https://openalex.org/W2741809807",
     *              doi: "https://doi.org/10.7717/peerj.4375",
     *              mag: 2741809807,
     *              pmid: "https://pubmed.ncbi.nlm.nih.gov/29456894",
     *              pmcid: "https://www.ncbi.nlm.nih.gov/pmc/articles/PMC5815332/" }
     */
    public $ids;

    /**
     * A HostVenue object describing how and where this work is being hosted online.
     * @var object
     * @see https://docs.openalex.org/about-the-data/work#the-hostvenue-object
     * @example host_venue: {
     *              id: "https://openalex.org/V1983995261",
     *               issn_l: "2167-8359",
     *               issn: [ "2167-8359" ],
     *               display_name: "PeerJ",
     *               publisher: "PeerJ",
     *               type: "journal",
     *               url: "https://doi.org/10.7717/peerj.4375",
     *               is_oa: null,
     *               version: null,
     *               license: null }
     */
    public $host_venue;

    /**
     * The type or genre of the work.
     * @var string
     * @see https://api.crossref.org/types
     * @example type: "journal-article"
     */
    public $type;

    /**
     * Information about the access status of this work, as an OpenAccess object.
     * @var object
     * @see https://docs.openalex.org/about-the-data/work#the-openaccess-object
     * @example open_access: { is_oa: true, oa_status: "gold", oa_url: "https://peerj.com/articles/4375.pdf" },
     */
    public $open_access;

    /**
     * List of Authorships objects, each representing an author and their institution.
     * @var object (list)
     * @see https://docs.openalex.org/about-the-data/work#the-authorship-object
     * @example authorships: [
     *      {
     *          author_position: "first",
     *          author: {
     *              id: "https://openalex.org/A1969205032",
     *              display_name: "Heather A. Piwowar",
     *              orcid: "https://orcid.org/0000-0003-1613-5981" },
     *          institutions: [ {
     *              id: "https://openalex.org/I4200000001",
     *              display_name: "OurResearch",
     *              ror: "https://ror.org/02nr0ka47",
     *              country_code: "US",
     *              type: "nonprofit" } ]
     *      }, ]
     */
    public $authorships;

    /**
     * The number of citations to this work. These are the times that other works have cited this work.
     * @var integer
     * @see
     * @example cited_by_count: 382
     */
    public $cited_by_count;

    /**
     * Old-timey bibliographic info for this work. This is mostly useful only in citation/reference contexts.
     * These are all strings because sometimes you'll get fun values like "Spring" and "Inside cover".
     * @var object
     * @see
     * @example biblio: { volume: "495", issue: "7442", first_page: "437", last_page: "440" }
     */
    public $biblio;

    /**
     * True if we know this work has been retracted. False if we don't know or not retracted.
     * @var boolean
     * @see
     * @example is_retracted: false
     */
    public $is_retracted;

    /**
     * True if we think this work is
     * @var boolean
     * @see https://en.wikipedia.org/wiki/Paratext
     * @example is_paratext: false
     */
    public $is_paratext;

    /**
     *  List of dehydrated Concepts objects
     * @var object (list)
     * @see https://docs.openalex.org/about-the-data/concept
     * @example concepts: [
     *      {
     *          id: "https://openalex.org/C2778793908",
     *          wikidata: "https://www.wikidata.org/wiki/Q5122404",
     *          display_name: "Citation impact",
     *          level: 3,
     *          score: 0.459309
     *      }, ]
     */
    public $concepts;

    /**
     * List of MeSH tag objects. Only works found in PubMed have MeSH tags; for all other works, this is an empty list.
     * @var object (list)
     * @see https://www.nlm.nih.gov/mesh/meshhome.html https://pubmed.ncbi.nlm.nih.gov/
     * @example mesh: [
     *      {
     *          descriptor_ui: "D017712",
     *          descriptor_name: "Peer Review, Research",
     *           qualifier_ui: "Q000379",
     *          qualifier_name: "methods",
     *          is_major_topic: false
     *      }, ]
     */
    public $mesh;

    /**
     * List of HostVenue objects describing places this work lives. This work's primary hosting venue isn't in this list; it's at host_venue.
     * @var object (list)
     * @see https://docs.openalex.org/about-the-data/work#the-hostvenue-object https://docs.openalex.org/about-the-data/work#host_venue
     * @example alternate_host_venues: [
     *      {
     *          id: null,
     *          display_name: "Europe PMC",
     *          type: "repository",
     *          url: "http://europepmc.org/articles/pmc5815332?pdf=render",
     *          is_oa: true,
     *          version: "publishedVersion",
     *          license: "cc-by" }, ]
     */
    public $alternate_host_venues;

    /**
     * OpenAlex IDs for works that this work cites. These are citations that go from this work out to another work.
     * @var object (list)
     * @see https://docs.openalex.org/about-the-data#the-openalex-id
     * @example referenced_works: [ "https://openalex.org/W2753353163", "https://openalex.org/W2785823074" ]
     */
    public $referenced_works;

    /**
     * OpenAlex IDs for works related to this work.
     * @var object (list)
     * @see https://docs.openalex.org/about-the-data#the-openalex-id
     * @example related_works: [ "https://openalex.org/W2753353163", "https://openalex.org/W2785823074" ]
     */
    public $related_works;

    /**
     * The abstract of the work, as an inverted index, which encodes information about the abstract's words and their
     * positions within the text. Like Microsoft Academic Graph, OpenAlex doesn't include plaintext abstracts
     * due to legal constraints.
     * @var object
     * @see https://en.wikipedia.org/wiki/Inverted_index https://docs.microsoft.com/en-us/academic-services/graph/
     * resources-faq#what-format-are-paper-abstracts-published-in
     * @example abstract_inverted_index: {
     *          Despite: [ 0 ],
     *          growing: [ 1 ],
     *          interest: [ 2 ],
     *          in: [ 3, 57, 73, 110, 122 ],
     *          Open: [ 4, 201 ],
     *          Access: [ 5 ], }
     */
    public $abstract_inverted_index;

    /**
     * Cited by api url
     * @var
     * @see https://docs.openalex.org/about-the-data/work#cited_by_api_url
     * @example
     */
    public $cited_by_api_url;

    /**
     * Counts by year
     * @var
     * @see https://docs.openalex.org/about-the-data/work#counts_by_year
     * @example
     */
    public $counts_by_year;

    /**
     * The last time anything in this Work object changed, expressed as an ISO 8601 date string. This date is
     * updated for any change at all, including increases in various counts.
     * @var string
     * @see https://en.wikipedia.org/wiki/ISO_8601
     * @example updated_date: "2022-01-02T00:22:35.180390"
     */
    public $updated_date;

    /**
     * The date this Work object was created in the OpenAlex dataset, expressed as an ISO 8601 date string.
     * @var string
     * @see https://en.wikipedia.org/wiki/ISO_8601
     * @example created_date: "2017-08-08"
     */
    public $created_date;
}