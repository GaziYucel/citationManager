<?php
/**
 * @file plugins/generic/optimetaCitations/classes/Model/AuthorModel.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class AuthorModel
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Authors are people who create works.
 */

namespace APP\plugins\generic\optimetaCitations\classes\Model;

class AuthorModel
{
    /**
     * The ORCID ID for this author. ORCID global and unique ID for authors.
     *
     * @var string
     * @see
     * @example "https://orcid.org/0000-0001-6187-6610"
     */
    public string $orcid;

    /**
     * The name of the author as a single string.
     *
     * @var string
     * @see
     * @example "Jason Priem"
     */
    public string $display_name;

    /**
     * The given name of the author as a single string.
     *
     * @var string
     * @see
     * @example "Jason"
     */
    public string $given_name;

    /**
     * The family name of the author as a single string.
     *
     * @var string
     * @see
     * @example "Priem"
     */
    public string $family_name;

    /**
     * The number of Works this this author has created.
     *
     * @var integer
     * @see
     * @example 38
     */
    public int $works_count;

    /**
     * The total number Works that cite a work this author has created.
     *
     * @var integer
     * @see
     * @example 38
     */
    public int $cited_by_count;

    /**
     * Author.works_count and Author.cited_by_count for each of the last ten years, binned by year.
     * To put it another way: each year, you can see how many works this author published,
     * and how many times they got cited.
     *
     * @var object (list)
     * @see
     * @example [ { year: 2022, works_count: 0, cited_by_count: 8 }, { year: 2021, works_count: 1, cited_by_count: 252 } ]
     */
    public object $counts_by_year;

    /**
     * The last time anything in this author object changed, expressed as an ISO 8601 date string.
     * This date is updated for any change at all, including increases in various counts.
     *
     * @var string
     * @see
     * @example "2022-01-02T00:00:00"
     */
    public string $updated_date;

    /**
     * The date this Author object was created in the OpenAlex dataset, expressed as an ISO 8601 date string.
     *
     * @var string
     * @see
     * @example "2017-08-08"
     */
    public string $created_date;

    /**
     * The Wikidata QID for this work
     *
     * @var string
     * @see https://www.wikidata.org/wiki/Q43649390
     * @example Q43649390
     */
    public string $wikidata_qid;

    /**
     * The OpenAlex ID for this work
     *
     * @var string
     * @see https://docs.openalex.org/about-the-data#the-openalex-id
     * @example W2741809807
     */
    public string $openalex_id;
}
