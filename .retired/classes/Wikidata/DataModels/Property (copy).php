<?php
/**
 * @file plugins/generic/citationManager/Wikidata/DataModels/Property.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Property
 * @ingroup plugins_generic_citationmanager
 *
 * @brief Properties on Wikidata. Downloaded from https://hay.toolforge.org/propbrowse
 */

// wikibase-item, monolingualtext, time, string, external-id

namespace APP\plugins\generic\citationManager\classes\Wikidata\DataModels;

class Property2
{
    public array $instanceOf = [
        "datatype" => "wikibase-item",
        "id" => "P31",
        "label" => "instance of",
        "description" => "that class of which this subject is a particular example and member; different from P279 (subclass of); for example: K2 is an instance of mountain; volcano is a subclass of mountain (and an instance of volcanic landform)",
        "aliases" => [
            "is a",
            "is an",
            "unique individual of",
            "unitary element of class",
            "rdf:type",
            "type",
            "∈",
            "example of",
            "is of type",
            "has type"
        ],
        "example" => [
            8023,
            772466,
            177866,
            2225
        ],
        "types" => []
    ];

    public array $title = [
        "datatype" => "monolingualtext",
        "id" => "P1476",
        "label" => "title",
        "description" => "published name of a work, such as a newspaper article, a literary work, piece of music, a website, or a performance work",
        "aliases" => [
            "original title",
            "article",
            "known as",
            "full title",
            "headline",
            "titled",
            "name"
        ],
        "example" => [
            180445,
            20124
        ],
        "types" => [
            "to indicate a source",
            "Wikidata name property",
            "Wikidata qualifier",
            "for items about works"
        ]
    ];

    public array $author = [
        "datatype" => "wikibase-item",
        "id" => "P50",
        "label" => "author",
        "description" => "main creator(s) of a written work (use on works, not humans); use P2093 (author name string) when Wikidata item is unknown or does not exist",
        "aliases" => [
            "written by",
            "writer",
            "creator",
            "playwright",
            "poet",
            "maker"
        ],
        "example" => [
            170583,
            8275,
            15228,
            147787,
            1951383,
            1751798
        ],
        "types" => [
            "for items about works",
            "for items about people",
            "to indicate a source"
        ]
    ];

    public array $publicationDate = [
        "datatype" => "time",
        "id" => "P577",
        "label" => "publication date",
        "description" => "date or point in time when a work was first published or released",
        "aliases" => [
            "air date",
            "airdate",
            "be published during",
            "be published in",
            "broadcast date",
            "date of first publication",
            "date of publication",
            "date of release",
            "date published",
            "date released",
            "dop",
            "first publication",
            "first published",
            "first released",
            "initial release",
            "launch date",
            "launched",
            "pubdate",
            "publication",
            "publication time",
            "published",
            "release date",
            "released",
            "released in",
            "time of publication",
            "was published during",
            "was published in",
            "year of publication",
            "issued"
        ],
        "example" => [
            19983493,
            214723,
            3132823,
            48247091
        ],
        "types" => [
            "to indicate a source",
            "for items about works"
        ]
    ];

    public array $publishedIn = [
        "datatype" => "wikibase-item",
        "id" => "P1433",
        "label" => "published in",
        "description" => "larger work that a given work was published in, like a book, journal or music album",
        "aliases" => [
            "on the tracklist of",
            "venue",
            "part of work",
            "published in journal",
            "album",
            "music album",
            "track on album",
            "chapter of",
            "article of",
            "essay of",
            "track of",
            "track on",
            "song on album",
            "song on",
            "work",
            "volume of",
            "tome of",
            "volume of the book",
            "tome of the book"
        ],
        "example" => [
            3576923,
            15715588,
            3478941
        ],
        "types" => [
            "for items about works",
            "to indicate a source"
        ]
    ];

    public array $volume = [
        "datatype" => "string",
        "id" => "P478",
        "label" => "volume",
        "description" => "volume of a book or music release in a collection/series or a published collection of journal issues in a serial publication",
        "aliases" => [
            "volume of a book",
            "volume of serial",
            "vol.",
            "tome",
            "numbering of part",
            "series numbering",
            "numbering in series",
            "number of series",
            "number of part",
            "part number",
            "volume number",
            "numbering of volume"
        ],
        "example" => [
            62092410,
            3020388,
            7065737
        ],
        "types" => [
            "to indicate a source",
            "with datatype string that is not an external identifier"
        ]
    ];

    public array $citesWork = [
        "datatype" => "wikibase-item",
        "id" => "P2860",
        "label" => "cites work",
        "description" => "citation from one creative or scholarly work to another",
        "aliases" => [
            "bibliographic citation",
            "citation"
        ],
        "example" => [
            21172284,
            24245131,
            7310435,
            2107009
        ],
        "types" => [
            "for items about works"
        ]
    ];

    public array $doi = [
        "datatype" => "external-id",
        "id" => "P356",
        "label" => "DOI",
        "description" => "serial code used to uniquely identify digital objects like academic papers (use upper case letters only)",
        "aliases" => [
            "Digital Object Identifier"
        ],
        "example" => [
            15567682,
            84371583
        ],
        "types" => [
            "representing a unique identifier"
        ]
    ];

    public array $orcidId = [
        "datatype" => "external-id",
        "id" => "P496",
        "label" => "ORCID iD",
        "description" => "identifier for a person",
        "aliases" => [
            "Open Research Contributor ID",
            "ORC ID",
            "Open Researcher and Contributor ID",
            "ORCiD",
            "ORCID iD",
            "ORCID ID"
        ],
        "example" => [
            8134165,
            6290611,
            193755
        ],
        "types" => []
    ];

    /**
     * Property constructor
     * property['default'] added dynamically
     *
     * @param bool|null $isTestMode
     */
    function __construct(?bool $isTestMode = false)
    {
        $file = 'PropertyProd.json';
        if ($isTestMode) $file = 'PropertyTest.json';

        $properties =
            json_decode(
                file_get_contents(
                    realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . $file
                ),
                true
            );

        foreach ($properties as $key => $value) {
            if (!property_exists($this, $key)) continue;

            $property = $this->$key;

            $property['id'] = $value['id'];
            $property['default'] = $value['default'];

            if ($isTestMode)
                $property['label'] = $property['pid'] . ': ' . $property['label'];

            $this->$key = $property;
        }
    }
}
