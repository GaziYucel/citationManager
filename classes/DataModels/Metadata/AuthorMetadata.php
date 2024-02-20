<?php
/**
 * @file classes/DataModels/AuthorMetadata.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class AuthorMetadata
 * @brief Metadata for Author
 */

namespace APP\plugins\generic\citationManager\classes\DataModels\Metadata;

class AuthorMetadata
{
    /** @var int|null The author id in OJS. */
    public ?int $id = null;

    /** @var string|null The ORCID ID for this author. */
    public ?string $orcid_id = null;

    /** @var string|null $openalex_id OpenAlex ID. */
    public ?string $openalex_id = null;

    /** @var string|null $wikidata_id Wikidata QID. */
    public ?string $wikidata_id = null;
}
