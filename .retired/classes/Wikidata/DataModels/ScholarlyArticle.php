<?php
/**
 * @file plugins/generic/citationManager/classes/Wikidata/DataModels/ScholarlyArticle.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class ScholarlyArticle
 * @ingroup plugins_generic_citationmanager
 *
 * @brief ScholarlyArticle are scholarly documents like journal articles, books, datasets, and theses.
 */

namespace APP\plugins\generic\citationManager\classes\Wikidata\DataModels;

class ScholarlyArticle
{
    /**
     * @var array|array[] Labels
     */
    public array $labels = [
        "en" => [
            "language" => "en",
            "value" => ""
        ]
    ];

    /**
     * @var array Default claim
     */
    public array $defaultClaim = [
        "mainsnak" => [
            "snaktype" => "value",
            "property" => "",
            "datavalue" => [
                "value" => "",
                "type" => "string"
            ]
        ],
        "type" => "statement",
        "rank" => "normal"
    ];

    /**
     * @var array Claim of type point in time
     */
    public array $pointInTimeClaim = [
        "mainsnak" => [
            "snaktype" => "value",
            "property" => "",
            "datavalue" => [
                "value" => [
                    "time" => "",
                    "timezone" => 0,
                    "before" => 0,
                    "after" => 0,
                    "precision" => 11,
                    "calendarmodel" => "http://www.wikidata.org/entity/Q1985727"
                ],
                "type" => "time"
            ],
            "datatype" => "time"
        ],
        "type" => "statement",
        "rank" => "normal"
    ];

    /**
     * @var array Claim of type citation
     */
    public array $citationClaim = [
        "entity-type" => "item",
        "numeric-id" => 0
    ];

    public function getLabelAsJson(string $ojsLocale, string $label): array
    {
        $languageModel = new Language();
        $labels = $this->labels;
        $labels[$languageModel->defaultLanguage]["value"] = $label;

        $wikiDataLocale = $languageModel->getLanguageCode($ojsLocale);

        if ($wikiDataLocale !== $languageModel->defaultLanguage) {
            $labels[$wikiDataLocale] = [
                "language" => $wikiDataLocale,
                "value" => $label
            ];
        }

        return $labels;
    }

    public function getDefaultClaimAsJson(string $propertyId, string $value): array
    {
        $claim = $this->defaultClaim;

        $claim["mainsnak"]["property"] = $propertyId;
        $claim["mainsnak"]["datavalue"]["value"] = $value;

        return $claim;
    }

    public function getPointInTimeClaimAsJson(string $propertyId, string $value): array
    {
        $claim = $this->pointInTimeClaim;

        $claim["mainsnak"]["property"] = $propertyId;
        $claim["mainsnak"]["datavalue"]["value"]["time"] = $value;

        return $claim;
    }

    /**
     * Return citation claim as json
     *
     * @param string $propertyId
     * @param string $qid
     *
     * @return array
     */
    public function getCitationClaimAsJson(string $propertyId, string $qid): array
    {
        $claim = $this->citationClaim;

        $claim["numeric-id"] = str_replace(['q', 'Q'], '', $qid);

        return $claim;
    }
}
