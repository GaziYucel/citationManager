<?php
/**
 * @file External/OpenAlex/DataModels/Author.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Author
 * @brief Authors are people who create works.
 * @see https://docs.openalex.org/api-entities/authors
 */

namespace APP\plugins\generic\citationManager\classes\External\OpenAlex\DataModels;

class Author
{
    public static function getMappings(): array
    {
        return [
            'openalex_id' => ['author','id'],
            'orcid_id' => ['author','orcid'],
            'display_name' => ['author','display_name'],
            'given_name' => ['author','display_name'],
            'family_name' => ['author','display_name']
        ];
    }
}
