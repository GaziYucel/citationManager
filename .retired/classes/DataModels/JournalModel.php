<?php
/**
 * @file classes/DataModels/Citation/JournalModel.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class JournalModel
 * @brief Journals are a where articles are published.
 */

namespace APP\plugins\generic\citationManager\classes\DataModels\Citation;

class JournalModel
{
    /** @var string|null $issn The ISSN identifying ID for journals. */
    public ?string $issn = null;

    /** @var string|null $issn_l The ISSN-L identifying / Canonical External ID for journals. */
    public ?string $issn_l = null;

    /** @var string|null $display_name The name of the journal as a single string. */
    public ?string $display_name = null;

    /** @var string|null $publisher The name of this journal's publisher. */
    public ?string $publisher = null;

    /** @var string|null $homepage_url The URL of the journal. */
    public ?string $homepage_url = null;

    /** @var string|null $wikidata_id The Wikidata QID. */
    public ?string $wikidata_id = null;

    /** @var string|null $openalex_id The OpenAlex ID. */
    public ?string $openalex_id = null;

    /** @var string|null $updated_date The time anything changed (ISO 8601 date string), e.g. "2022-01-02T00:22:35.180390". */
    public ?string $updated_date = null;

    /** @var bool|null $isProcessed Is this work processed or to be processed. */
    public ?bool $isProcessed = null;
}
