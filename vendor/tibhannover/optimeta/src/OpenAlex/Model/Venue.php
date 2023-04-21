<?php
/**
 * @file plugins/generic/optimetaCitations/vendor/tibhannover/optimeta/src/OpenAlex/Model/Venue.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Venue
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Venues are where works are hosted.
 */

namespace Optimeta\Shared\OpenAlex\Model;

class Venue
{
    /**
     * @var string
     * @desc The OpenAlex ID for this venue.
     * @see
     * @example id: "https://openalex.org/V1983995261"
     */
    public $id;

    /**
     * @var string
     * @desc The ISSN-L identifying this venue. This is the Canonical External ID for venues.
     * @see https://docs.openalex.org/about-the-data#canonical-external-ids
     * @example issn_l: "2167-8359"
     */
    public $issn_l;

    /**
     * @var array
     * @desc The ISSNs used by this venue. Many publications have multiple ISSNs, so ISSN-L should be used when possible.
     * @see https://en.wikipedia.org/wiki/International_Standard_Serial_Number
     * @example issn:["2167-8359"]
     */
    public $issn;

    /**
     * @var string
     * @desc The name of the venue.
     * @see
     * @example display_name: "PeerJ"
     */
    public $display_name;

    /**
     * @var string
     * @desc The name of this venue's publisher. Publisher is a tricky category, as journals often change publishers, publishers merge, publishers have subsidiaries ("imprints"), and of course no one is consistent in their naming. In the future, we plan to roll out support for a more structured publisher field, but for now it's just a string.
     * @see
     * @example publisher: "Peerj"
     */
    public $publisher;

    /**
     * @var integer
     * @desc The number of Works this venue hosts.
     * @see
     * @example works_count: 20184
     */
    public $works_count;

    /**
     * @var integer
     * @desc The total number of Works that cite a Work hosted in this venue.
     * @see
     * @example cited_by_count: 133702
     */
    public $cited_by_count;

    /**
     * @var boolean
     * @desc
     * @see
     * @example is_oa: true
     */
    public $is_oa;

    /**
     * @var boolean
     * @desc
     * @see
     * @example is_in_doaj: true
     */
    public $is_in_doaj;

    /**
     * @var string
     * @desc
     * @see
     * @example homepage_url: "http://www.peerj.com/"
     */
    public $homepage_url;

    /**
     * @var object
     * @desc All the external identifiers that we know about for this venue. IDs are expressed as URIs whenever possible.
     * @see
     * @example ids: { openalex: "https://openalex.org/V1983995261", issn_l: "2167-8359", issn: [ "2167-8359" ], mag: 1983995261 }
     */
    public $ids;

    /**
     * @var object (list)
     * @desc works_count and cited_by_count for each of the last ten years, binned by year. To put it another way: each year, you can see how many new works this venue started hosting, and how many times any work in this venue got cited.
     * @see
     * @example counts_by_year: [ { year: 2021, works_count: 4338, cited_by_count: 127268 }, { year: 2020, works_count: 4363, cited_by_count: 119531 } ]
     */
    public $counts_by_year;

    /**
     * @var string
     * @desc A URL that will get you a list of all this venue's Works.
     * @see
     * @example works_api_url: "https://api.openalex.org/works?filter=host_venue.id:V1983995261",
     */
    public $works_api_url;

    /**
     * @var string
     * @desc The last time anything in this venue object changed, expressed as an ISO 8601 date string. This date is updated for any change at all, including increases in various counts.
     * @see
     * @example "2022-01-02T00:00:00"
     */
    public $updated_date;

    /**
     * @var string
     * @desc The date this venue object was created in the OpenAlex dataset, expressed as an ISO 8601 date string.
     * @see
     * @example "2017-08-08"
     */
    public $created_date;
}
