<?php
/**
 * @file plugins/generic/optimetaCitations/Wikidata/Model/Property.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Property
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Property on Wikidata.
 */

namespace APP\plugins\generic\optimetaCitations\classes\Wikidata\DataModels;

class Property
{
    public array $instanceOf = [
        'pid' => 'P31',
        'type' => 'item',
        'label' => 'instance of',
        'default' => 'Q13442814'
    ];

    public array $title = [
        'pid' => 'P1476',
        'type' => 'monolingual text',
        'label' => 'title',
        'default' => ''
    ];

    public array $author = [
        'pid' => 'P50',
        'type' => 'item',
        'label' => 'author',
        'default' => ''
    ];

    public array $authorNameString = [
        'pid' => 'P2093',
        'type' => 'string',
        'label' => 'author name string',
        'default' => ''
    ];

    public array $publicationDate = [
        'pid' => 'P577',
        'type' => 'point in time',
        'label' => 'publication date',
        'default' => ''
    ];

    public array $publishedIn = [
        'pid' => 'P1433',
        'type' => 'item',
        'label' => 'published in',
        'default' => ''
    ];

    public array $volume = [
        'pid' => 'P478',
        'type' => 'string',
        'label' => 'volume',
        'default' => ''
    ];

    public array $citesWork = [
        'pid' => 'P2860',
        'type' => 'item',
        'label' => 'cites work',
        'default' => ''
    ];

    public array $doi = [
        'pid' => 'P356',
        'type' => 'external identifier',
        'label' => 'DOI',
        'default' => ''
    ];

    function __construct(?bool $isProduction = false)
    {
        if(!$isProduction){
            foreach (json_decode(file_get_contents(
                realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'PropertyTest.json'), true) as $key => $value) {
                $prop = $this->$key;
                $prop['label'] = $prop['pid'] . ': ' . $prop['label'];
                $prop['pid'] = $value['pid'];
                $prop['default'] = $value['default'];

                $this->$key = $prop;
            }
        }
    }
}
