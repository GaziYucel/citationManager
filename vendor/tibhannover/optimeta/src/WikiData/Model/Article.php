<?php

namespace Optimeta\Shared\WikiData\Model;

class Article extends Item
{
    public array $labels = [
            "en" => [
                "language" => "en",
                "value" => ""
            ]
    ];

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

    public function getLabelAsJson(string $locale, string $label): array
    {
        $languages = new Languages();
        $labels = $this->labels;
        $labels[$languages->defaultLanguage]["value"] = $label;

        $language = $languages->getLanguageCode($locale);

        if($language !== $languages->defaultLanguage){
            $labels[$language] = [
                "language" => $language,
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
