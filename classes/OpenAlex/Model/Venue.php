<?php
/**
 * @file plugins/generic/optimetaCitations/OpenAlex/Model/Venue.php
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

namespace APP\plugins\generic\optimetaCitations\classes\OpenAlex\Model;

class Venue
{
    /**
     * The OpenAlex ID for this venue.
     * 
     * @var string
     * @see
     * @example id: "https://openalex.org/V1983995261"
     */
    public string $id;

    /**
     * The ISSN-L identifying this venue. This is the Canonical External ID for venues.
     * 
     * @var string
     * @see https://docs.openalex.org/about-the-data#canonical-external-ids
     * @example issn_l: "2167-8359"
     */
    public string $issn_l;

    /**
     * The ISSNs used by this venue. Many publications have multiple ISSNs, so ISSN-L should be used when possible.
     * 
     * @var string array
     * @see https://en.wikipedia.org/wiki/International_Standard_Serial_Number
     * @example issn:["2167-8359"]
     */
    public string $issn;

    /**
     * The name of the venue.
     * 
     * @var string
     * @see
     * @example display_name: "PeerJ"
     */
    public string $display_name;

    /**
     * The name of this venue's publisher. Publisher is a tricky category, as journals often change publishers, 
     * publishers merge, publishers have subsidiaries ("imprints"), and of course no one is consistent in their 
     * naming. In the future, we plan to roll out support for a more structured publisher field, 
     * but for now it's just a string.
     * 
     * @var string
     * @see
     * @example publisher: "Peerj"
     */
    public string $publisher;

    /**
     * The number of Works this venue hosts.
     * 
     * @var string int
     * @see
     * @example works_count: 20184
     */
    public string $works_count;

    /**
     * The total number of Works that cite a Work hosted in this venue.
     * 
     * @var string int
     * @see
     * @example cited_by_count: 133702
     */
    public string $cited_by_count;

    /**
     * Is this venue open access
     * 
     * @var string bool
     * @see
     * @example is_oa: true
     */
    public string $is_oa;

    /**
     * Is in DOAJ
     * 
     * @var string bool
     * @see
     * @example is_in_doaj: true
     */
    public string $is_in_doaj;

    /**
     * Homepage url
     * 
     * @var string
     * @see
     * @example homepage_url: "http://www.peerj.com/"
     */
    public string $homepage_url;

    /**
     * All the external identifiers that we know about for this venue. IDs are expressed as URIs whenever possible.
     * 
     * @var string object
     * @see
     * @example ids: { 
     *              openalex: "https://openalex.org/V1983995261", 
     *              issn_l: "2167-8359", 
     *              issn: [ "2167-8359" ], 
     *              mag: 1983995261 }
     */
    public string $ids;

    /**
     * works_count and cited_by_count for each of the last ten years, binned by year. To put it another way:
     * each year, you can see how many new works this venue started hosting, and how many times any work in
     * this venue got cited.
     * 
     * @var string object (list)
     * @see
     * @example counts_by_year: [ 
     *              { year: 2021, works_count: 4338, cited_by_count: 127268 }, 
     *              { year: 2020, works_count: 4363, cited_by_count: 119531 } ]
     */
    public string $counts_by_year;

    /**
     * A URL that will get you a list of all this venue's Works.
     * 
     * @var string
     * @see
     * @example works_api_url: "https://api.openalex.org/works?filter=host_venue.id:V1983995261",
     */
    public string $works_api_url;

    /**
     * The last time anything in this venue object changed, expressed as an ISO 8601 date string.
     * This date is updated for any change at all, including increases in various counts.
     * 
     * @var string
     * @see
     * @example "2022-01-02T00:00:00"
     */
    public string $updated_date;

    /**
     * The date this venue object was created in the OpenAlex dataset, expressed as an ISO 8601 date string.
     * 
     * @var string
     * @see
     * @example "2017-08-08"
     */
    public string $created_date;
}
