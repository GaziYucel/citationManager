<?php
/**
 * @file classes/DataModels/MetadataPublication.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class MetadataPublication
 * @brief Metadata for Publication
 */

namespace APP\plugins\generic\citationManager\classes\DataModels\Metadata;

class MetadataPublication
{
    /** @var string|null $openalex_id OpenAlex ID. */
    public ?string $openalex_id = null;

    /** @var string|null $wikidata_id Wikidata QID. */
    public ?string $wikidata_id = null;

    /** @var string|null $opencitations_id Open Citations ID. */
    public ?string $opencitations_id = null;

    /** @var string|null $github_issue_id GitHub Issue ID for Open Citations. */
    public ?string $github_issue_id = null;
}
