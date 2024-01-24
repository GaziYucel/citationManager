<?php
/**
 * @file plugins/generic/optimetaCitations/classes/DataModels/AuthorModel.php
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

namespace APP\plugins\generic\optimetaCitations\classes\DataModels;

class AuthorModel
{
    /**
     * The ORCID ID for this author. ORCID global and unique ID for authors.
     *
     * @var ?string
     * @see
     * @example "https://orcid.org/0000-0001-6187-6610"
     */
    public ?string $orcid = null;

    /**
     * The name of the author as a single string.
     *
     * @var ?string
     * @see
     * @example "John Doe"
     */
    public ?string $display_name = null;

    /**
     * The given name of the author as a single string.
     *
     * @var ?string
     * @see
     * @example "John"
     */
    public ?string $given_name = null;

    /**
     * The family name of the author as a single string.
     *
     * @var ?string
     * @see
     * @example "Doe"
     */
    public ?string $family_name = null;

    /**
     * The number of Works this author has created.
     *
     * @var ?integer
     * @see
     * @example 38
     */
    public ?int $works_count = null;

    /**
     * The total number Works that cite a work this author has created.
     *
     * @var ?integer
     * @see
     * @example 38
     */
    public ?int $cited_by_count = null;

    /**
     * Author.works_count and Author.cited_by_count for each of the last ten years, binned by year.
     * To put it another way: each year, you can see how many works this author published,
     * and how many times they got cited.
     *
     * @var ?object (list)
     * @see
     * @example [ { year: 2022, works_count: 0, cited_by_count: 8 }, { year: 2021, works_count: 1, cited_by_count: 252 } ]
     */
    public ?object $counts_by_year = null;

    /**
     * The last time anything in this author object changed, expressed as an ISO 8601 date string.
     * This date is updated for any change at all, including increases in various counts.
     *
     * @var ?string
     * @see
     * @example "2022-01-02T00:00:00"
     */
    public ?string $updated_date = null;

    /**
     * The date this Author object was created in the OpenAlex dataset, expressed as an ISO 8601 date string.
     *
     * @var ?string
     * @see
     * @example "2017-08-08"
     */
    public ?string $created_date = null;

    /**
     * List of PIDs.
     *
     * @var ?array
     * @see
     * @example [ 'wikidata' => 'Q43649390', ... ]
     */
    public ?array $pids = null;

    /**
     * List of external sources (urls) where this author can be found.
     *
     * @var ?array
     * @see
     * @example [ 'wikidata' => 'https://wikidata.org/wiki/Q43649390', ... ]
     */
    public ?array $external_sources = null;

    /**
     * Is this work processed or to be processed
     *
     * @var ?bool
     * @see
     * @example false
     */
    public ?bool $isProcessed = null;

    /* //todo: move properties below to pids and external_sources */

    /**
     * The Wikidata QID for this work
     *
     * @var ?string
     * @see https://www.wikidata.org/wiki/Q43649390
     * @example Q43649390
     */
    public ?string $wikidata_qid = null;

    /**
     * The OpenAlex ID for this work
     *
     * @var ?string
     * @see https://docs.openalex.org/about-the-data#the-openalex-id
     * @example W2741809807
     */
    public ?string $openalex_id = null;
}
