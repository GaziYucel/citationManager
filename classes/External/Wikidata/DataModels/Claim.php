<?php
/**
 * @file classes/External/Wikidata/DataModels/Claim.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Claim
 * * @brief Claim syntaxes for adding claims to a new item on Wikidata.
 */

namespace APP\plugins\generic\citationManager\classes\External\Wikidata\DataModels;

class Claim
{
    /**
     * Return labels for all locales
     *
     * @param string $locale
     * @param string $label
     * @return array[]
     */
    public function getLabels(string $locale, string $label): array
    {
        $labels = [
            'en' => [
                'language' => 'en',
                'value' => $label
            ]
        ];

        $languageModel = new Language();

        $localeWd = $languageModel->getLanguageCode($locale);

        if ($localeWd !== $languageModel->defaultLanguage) {
            $labels[$localeWd] = [
                'language' => $localeWd,
                'value' => $label
            ];
        }

        return $labels;
    }

    /**
     * Return wikibase item claim
     *
     * @param string $property
     * @param string $value
     * @return array
     */
    public function getWikibaseItem(string $property, string $value): array
    {
        return [
            'mainsnak' =>
                [
                    'snaktype' => 'value',
                    'property' => $property,
                    'datavalue' =>
                        [
                            'value' =>
                                [
                                    'entity-type' => 'item',
                                    'numeric-id' => str_replace(['q', 'Q'], '', $value),
                                    'id' => $value
                                ],
                            'type' => 'wikibase-entityid'
                        ],
                    'datatype' => 'wikibase-item'
                ],
            'type' => 'statement',
            'rank' => 'normal'
        ];
    }

    /**
     * Return claim instance of
     *
     * @param string $property
     * @param string $value
     * @return array
     */
    public function getInstanceOf(string $property, string $value): array
    {
        return $this->getWikibaseItem($property, $value);
    }

    /**
     * Return claim external id
     *
     * @param string $property
     * @param string $value
     * @return array
     */
    public function getExternalId(string $property, string $value): array
    {
        return [
            'mainsnak' =>
                [
                    'snaktype' => 'value',
                    'property' => $property,
                    'datavalue' =>
                        [
                            'value' => $value,
                            'type' => 'string'
                        ],
                    'datatype' => 'external-id'
                ],
            'type' => 'statement',
            'rank' => 'normal'
        ];
    }

    /**
     * Return claim mono lingual text
     *
     * @param string $property
     * @param string $value
     * @param string $language
     * @return array
     */
    public function getMonoLingualText(string $property, string $value, string $language): array
    {
        return [
            'mainsnak' =>
                [
                    'snaktype' => 'value',
                    'property' => $property,
                    'datavalue' => [
                        'value' =>
                            [
                                'text' => $value,
                                'language' => $language
                            ],
                        'type' => 'monolingualtext'
                    ],
                    'datatype' => 'monolingualtext'
                ],
            'type' => 'statement',
            'rank' => 'normal'
        ];
    }

    /**
     * Return point in time claim, e.g. "+2021-04-14T00:00:00Z"
     *
     * @param string $property
     * @param string $value
     * @return array
     */
    public function getPointInTime(string $property, string $value): array
    {
        return [
            "mainsnak" => [
                "snaktype" => "value",
                "property" => $property,
                "datavalue" => [
                    "value" => [
                        "time" => $value,
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
    }

    /**
     * Return claim mono lingual text
     *
     * @param string $property
     * @param string $value
     * @return array
     */
    public function getString(string $property, string $value): array
    {
        return [
            'mainsnak' =>
                [
                    'snaktype' => 'value',
                    'property' => $property,
                    'datavalue' =>
                        [
                            'value' => $value,
                            'type' => 'string'
                        ],
                    'datatype' => 'string'
                ],
            'type' => 'statement',
            'rank' => 'normal'
        ];
    }

    /**
     * Return wikibase item claim reference.
     * This is for adding claims to an existing item with action 'wbcreateclaim'
     *
     * @param string $value
     * @return array
     */
    public function getWikibaseItemReference(string $value): array
    {
        return [
            'entity-type' => 'item',
            'numeric-id' => str_replace(['q', 'Q'], '', $value)
        ];
    }
}
