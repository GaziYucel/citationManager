<?php
/**
 * @desc Authors are people who create works.
 */
namespace Optimeta\Citations\Model;

class AuthorModel
{
    /**
     * @var string
     * @desc The ORCID ID for this author. ORCID global and unique ID for authors.
     * @see
     * @example "https://orcid.org/0000-0001-6187-6610"
     */
    public $orcid;

    /**
     * @var string
     * @desc The name of the author as a single string.
     * @see
     * @example "Jason Priem"
     */
    public $name;

    /**
     * @var integer
     * @desc The number of Works this this author has created.
     * @see
     * @example 38
     */
    public $works_count;

    /**
     * @var integer
     * @desc The total number Works that cite a work this author has created.
     * @see
     * @example 38
     */
    public $cited_by_count;

    /**
     * @var object (list)
     * @desc Author.works_count and Author.cited_by_count for each of the last ten years, binned by year. To put it another way: each year, you can see how many works this author published, and how many times they got cited.
     * @see
     * @example [ { year: 2022, works_count: 0, cited_by_count: 8 }, { year: 2021, works_count: 1, cited_by_count: 252 } ]
     */
    public $counts_by_year;

    /**
     * @var string
     * @desc The last time anything in this author object changed, expressed as an ISO 8601 date string. This date is updated for any change at all, including increases in various counts.
     * @see
     * @example "2022-01-02T00:00:00"
     */
    public $updated_date;

    /**
     * @var string
     * @desc The date this Author object was created in the OpenAlex dataset, expressed as an ISO 8601 date string.
     * @see
     * @example "2017-08-08"
     */
    public $created_date;

    /**
     * @var string
     * @desc The Wikidata QID for this work
     * @see https://www.wikidata.org/wiki/Q43649390
     * @example Q43649390
     */
    public $wikidata_qid;

    /**
     * @var string
     * @desc The OpenAlex ID for this work
     * @see https://docs.openalex.org/about-the-data#the-openalex-id
     * @example W2741809807
     */
    public $openalex_id;
}
