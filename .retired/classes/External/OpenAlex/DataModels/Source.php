<?php
/**
 * @file External/OpenAlex/DataModels/Source.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Source
 * @brief Sources are where works are hosted.
 * @see https://docs.openalex.org/api-entities/sources
 */

namespace APP\plugins\generic\citationManager\classes\External\OpenAlex\DataModels;

class Source
{
    public static function getMappings(): array
    {
        return [
            'display_name' => ['locations', '0', 'source', 'display_name'],
            'publisher' => ['locations', '0', 'source', 'host_organization_name'],
            'openalex_id' => ['locations', '0', 'source', 'id'],
        ];
    }
}
