<?php
/**
 * @file plugins/generic/optimetaCitations/classes/OpenAlex/Enrich.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class OpenAlex
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief OpenAlex class for OpenAlex
 */

namespace APP\plugins\generic\optimetaCitations\classes\OpenAlex;

use APP\plugins\generic\optimetaCitations\classes\Model\AuthorModel;
use APP\plugins\generic\optimetaCitations\classes\Model\CitationModel;
use APP\plugins\generic\optimetaCitations\classes\OpenAlex\Model\Work;
use APP\plugins\generic\optimetaCitations\classes\PID\Orcid;
use APP\plugins\generic\optimetaCitations\OptimetaCitationsPlugin;
use APP\plugins\generic\optimetaCitations\classes\PID\Doi;

class Enrich
{
    /**
     * @var OptimetaCitationsPlugin
     */
    public OptimetaCitationsPlugin $plugin;

    /**
     * @var Api
     */
    public Api $api;

    public function __construct(OptimetaCitationsPlugin $plugin)
    {
        $this->plugin = $plugin;

        $this->api = new Api($this->plugin, $this->plugin::OPTIMETA_CITATIONS_OPENALEX_API_URL);
    }

    /**
     * Get all information from OpenAlex and return as CitationModel
     *
     * @param CitationModel $citation
     * @return CitationModel
     */
    public function getEnriched(CitationModel $citation): CitationModel
    {
        $objDoi = new Doi();
        $doi = $objDoi->removePrefixFromUrl($citation->doi);

        $openAlexWork = new Work();
        $openAlexArray = $this->api->getObjectFromApi($doi);
        foreach ($openAlexArray as $key => $value) {
            if (property_exists($openAlexWork, $key)) {
                $openAlexWork->$key = $value;
            }
        }

        $citation->title = $openAlexWork->title;
        $citation->publication_year = $openAlexWork->publication_year;
        $citation->publication_date = $openAlexWork->publication_date;
        $citation->type = $openAlexWork->type;

        $objOrcid = new Orcid();
        for ($i = 0; $i < count((array)$openAlexWork->authorships); $i++) {
            $author = new AuthorModel();
            $author->orcid = $objOrcid->removePrefixFromUrl(
                $openAlexWork->authorships[$i]['author']['orcid']);

            $author->display_name = $openAlexWork->authorships[$i]['author']['display_name'];
            $authorDisplayNameParts = explode(' ', trim($author->display_name));
            if (count($authorDisplayNameParts) > 1) {
                $author->family_name = array_pop($authorDisplayNameParts);
                $author->given_name = implode(' ', $authorDisplayNameParts);
            }
            $author->openalex_id = $this->removeOpenAlexOrgFromUrl($openAlexWork->authorships[$i]['author']['id']);
            $citation->authors[] = (array)$author;
        }
        $citation->cited_by_count = $openAlexWork->cited_by_count;

        if (!empty($openAlexWork->biblio['volume'])) $citation->volume = $openAlexWork->biblio['volume'];
        if (!empty($openAlexWork->biblio['issue'])) $citation->issue = $openAlexWork->biblio['issue'];
        $citation->pages = 0;
        if (!empty($openAlexWork->biblio['first_page'])) $citation->first_page = $openAlexWork->biblio['first_page'];
        if (!empty($openAlexWork->biblio['last_page'])) $citation->last_page = $openAlexWork->biblio['last_page'];

        $citation->is_retracted = $openAlexWork->is_retracted;
        $citation->updated_date = $openAlexWork->updated_date;
        $citation->created_date = $openAlexWork->created_date;

        if (!empty($openAlexWork->host_venue['issn_l'])) $citation->venue_issn_l = $openAlexWork->host_venue['issn_l'];
        if (!empty($openAlexWork->host_venue['display_name'])) $citation->venue_name = $openAlexWork->host_venue['display_name'];
        if (!empty($openAlexWork->host_venue['publisher'])) $citation->venue_publisher = $openAlexWork->host_venue['publisher'];
        if (!empty($openAlexWork->host_venue['is_oa'])) $citation->venue_is_oa = $openAlexWork->host_venue['is_oa'];
        if (!empty($openAlexWork->host_venue['id'])) $citation->venue_openalex_id = $this->removeOpenAlexOrgFromUrl($openAlexWork->host_venue['id']);

        $citation->openalex_id = $this->removeOpenAlexOrgFromUrl($openAlexWork->id);

        if (!empty($citation->openalex_id)) {
            $citation->openalex_url = $this->plugin::OPTIMETA_CITATIONS_OPENALEX_URL . '/' . $citation->openalex_id;
            $citation->isProcessed = true;
        }

        return $citation;
    }

    /**
     * Remove OpenAlex prefix from URL
     *
     * @param ?string $url
     * @return string
     */
    public function removeOpenAlexOrgFromUrl(?string $url): string
    {
        if (empty($url)) {
            return '';
        }
        return str_replace($this->plugin::OPTIMETA_CITATIONS_OPENALEX_URL . '/', '', $url);
    }
}