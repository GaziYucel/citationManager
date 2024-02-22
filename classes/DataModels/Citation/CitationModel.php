<?php
/**
 * @file classes/DataModels/CitationModel.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class CitationModel
 * @brief Citations are scholarly documents like journal articles, books, datasets, and theses.
 */

namespace APP\plugins\generic\citationManager\classes\DataModels\Citation;

class CitationModel
{
    /** @var string|null The DOI for the work. */
    public ?string $doi = null;

    /** @var string|null The title of this work. */
    public ?string $title = null;

    /** @var int|null The year this work was published. */
    public ?int $publication_year = null;

    /** @var string|null The publication date, formatted as an ISO 8601 date e.g. 2018-02-13. */
    public ?string $publication_date = null;

    /** @var string|null The type or genre of the work, e.g. journal-article. */
    public ?string $type = null;

    /** @var string|null The volume of the issue of the journal, e.g. 495. */
    public ?string $volume = null;

    /** @var string|null The issue of the journal, e.g. 7442. */
    public ?string $issue = null;

    /** @var string|null The number of pages of the work/article, e.g. 4. */
    public ?string $pages = null;

    /** @var string|null The first page of the work/article, e.g. 49. */
    public ?string $first_page = null;

    /** @var string|null The last page of the work/article, e.g. 59. */
    public ?string $last_page = null;

    /** @var string|null The abstract of this work. */
    public ?string $abstract = null;

    /** @var array|null List of Author objects, e.g. [ AuthorModel, AuthorModel, ... ] */
    public ?array $authors = null;

    /* @var string|null Name of the journal */
    public ?string $journal_name = null;

    /* @var string|null Issn_l of the journal */
    public ?string $journal_issn_l = null;

    /* @var string|null Publisher name of the journal */
    public ?string $journal_publisher = null;

    /** @var string|null URL for the work. */
    public ?string $url = null;

    /** @var string|null URN for the work. */
    public ?string $urn = null;

    /** @var string|null Arxiv id. */
    public ?string $arxiv_id = null;

    /** @var string|null Handle id. */
    public ?string $handle_id = null;

    /** @var string|null OpenAlex ID. */
    public ?string $openalex_id = null;

    /** @var string|null Wikidata QID. */
    public ?string $wikidata_id = null;

    /** @var string|null Open Citations ID. */
    public ?string $opencitations_id = null;

    /** @var string|null GitHub Issue ID for Open Citations. */
    public ?string $github_issue_id = null;

    /** @var string|null The unchanged raw citation. */
    public ?string $raw = null;

    /** @var bool|null Is processed / structured. */
    public ?bool $isProcessed = null;

}
