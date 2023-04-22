<?php
/**
 * @file plugins/generic/optimetaCitations/classes/Model/CitationModel.inc.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class CitationModel
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Citations are scholarly documents like journal articles, books, datasets, and theses.
 */

namespace Optimeta\Citations\Model;

class CitationModel extends WorkModel
{
    /**
     * The unchanged raw citation
     * @var string
     */
    public $raw;

    /**
     * Migrates to current CitationModel
     * @param string $citations
     * @return array
     */
    public static function migrate(string $citations): array
    {
        if (empty($citations) || !is_array(json_decode($citations, true))) return [];

        $citationsIn = json_decode($citations, true);
        $citationsOut = [];

        foreach ($citationsIn as $index => $row) {
            if (is_object($row) || is_array($row)) {
                $citation = new CitationModel();

                foreach ($row as $key => $value) {
                    switch ($key) {
                        case '-_-add key here to do custom changes or mappings-_-':
                            break;
                        default:
                            if (property_exists($citation, $key)) {
                                $citation->$key = $value;
                            }
                    }
                }

                $citationsOut[] = (array)$citation;
            }
        }

        return $citationsOut;
    }
}
