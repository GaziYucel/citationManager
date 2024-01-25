<?php
/**
 * @file plugins/generic/optimetaCitations/classes/Wikidata/Model/Article.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Article
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Articles are scholarly documents like journal articles, books, datasets, and theses.
 */

namespace APP\plugins\generic\optimetaCitations\classes\Wikidata\Model;

class Article
{
    /**
     * Labels
     * @var array|array[]
     */
    public array $labels = [
        "en" => [
            "language" => "en",
            "value" => ""
        ]
    ];

    /**
     * Default claim
     * @var array
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
     * Claim of type point in time
     * @var array
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
}
