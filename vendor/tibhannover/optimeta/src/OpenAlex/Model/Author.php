<?php
/**
 * @file plugins/generic/optimetaCitations/vendor/tibhannover/optimeta/src/OpenAlex/Model/Author.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Author
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Authors are people who create works.
 */

namespace Optimeta\Shared\OpenAlex\Model;

class Author
{
    /**
     * The OpenAlex ID for this author.
     * @var string
     * @see
     * @example id: "https://openalex.org/A2208157607"
     */
    public $id;

    /**
     * The ORCID ID for this author. ORCID global and unique ID for authors.
     * @var string
     * @see
     * @example orcid: "https://orcid.org/0000-0001-6187-6610"
     */
    public $orcid;

    /**
     * The name of the author as a single string.
     * @var string
     * @see
     * @example display_name: "Jason Priem"
     */
    public $display_name;

    /**
     * Other ways that we've found this author's name displayed.
     * @var object (list)
     * @see
     * @example display_name_alternatives: [ "Jason R Priem" ]
     */
    public $display_name_alternatives;

    /**
     * The number of Works this author has created.
     * @var integer
     * @see
     * @example works_count: 38
     */
    public $works_count;

    /**
     * The total number Works that cite a work this author has created.
     * @var integer
     * @see
     * @example cited_by_count: 38
     */
    public $cited_by_count;

    /**
     * All the external identifiers that we know about for this author. IDs are expressed as URIs whenever possible.
     * @var object
     * @see
     * @example ids: {
     *              openalex: "https://openalex.org/A2208157607",
     *              orcid: "https://orcid.org/0000-0001-6187-6610",
     *              scopus: "http://www.scopus.com/inward/authorDetails.url?authorID=36455008000&partnerID=MN8TOARS",
     *              mag: 2208157607 },
     */
    public $ids;

    /**
     * This author's last known institutional affiliation. In this context "last known" means that we took all
     * the Works  where this author has an institutional affiliation, sorted them by publication date,
     * and selected the most recent one.
     * @var object
     * @see
     * @example last_known_institution: {
     *               id: "https://openalex.org/I4200000001",
     *               ror: "https://ror.org/02nr0ka47",
     *               display_name: "OurResearch",
     *               country_code: "CA",
     *               type: "nonprofit" },
     */
    public $last_known_institution;

    /**
     * Author.works_count and Author.cited_by_count for each of the last ten years, binned by year.
     * To put it another way: each year, you can see how many works this author published,
     * and how many times they got cited.
     * @var object (list)
     * @see
     * @example [
     *               { year: 2022, works_count: 0, cited_by_count: 8 },
     *               { year: 2021, works_count: 1, cited_by_count: 252 } ]
     */
    public $counts_by_year;

    /**
     * A URL that will get you a list of all this author's works.
     * @var string
     * @see
     * @example works_api_url: "https://api.openalex.org/works?filter=author.id:A2208157607",
     */
    public $works_api_url;

    /**
     * The last time anything in this author object changed, expressed as an ISO 8601 date string.
     * This date is updated for any change at all, including increases in various counts.
     * @var string
     * @see
     * @example "2022-01-02T00:00:00"
     */
    public $updated_date;

    /**
     * The date this Author object was created in the OpenAlex dataset, expressed as an ISO 8601 date string.
     * @var string
     * @see
     * @example "2017-08-08"
     */
    public $created_date;
}
