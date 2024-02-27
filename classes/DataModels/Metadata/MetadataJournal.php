<?php
/**
 * @file classes/DataModels/MetadataJournal.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class MetadataJournal
 * @brief Metadata for Journal
 */

namespace APP\plugins\generic\citationManager\classes\DataModels\Metadata;

class MetadataJournal
{
    /** @var string|null $openalex_id OpenAlex ID. */
    public ?string $openalex_id = null;

    /** @var string|null $wikidata_id Wikidata QID. */
    public ?string $wikidata_id = null;
}
