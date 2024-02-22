<?php
/**
 * @file External/OpenAlex/DataModels/Work.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Work
 * @brief Works are scholarly documents like journal articles, books, datasets, and theses.
 * @see https://docs.openalex.org/api-entities/works
 */

namespace APP\plugins\generic\citationManager\classes\External\OpenAlex\DataModels;

class Work
{
    public static function getMappings(): array
    {
        return [
            'openalex_id' => 'id',
            'title' => 'title',
            'publication_year' => 'publication_year',
            'publication_date' => 'publication_date',
            'type' => 'type_crossref',
            'volume' => ['biblio', 'volume'],
            'issue' => ['biblio', 'issue'],
            'first_page' => ['biblio', 'first_page'],
            'last_page' => ['biblio', 'last_page'],
            'updated_date' => 'updated_date',
            'journal' => new Source(),
            'authors' => [new Author()]
        ];
    }
}
