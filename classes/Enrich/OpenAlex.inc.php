<?php
namespace Optimeta\Citations\Enrich;

import('plugins.generic.optimetaCitations.classes.Helpers');

use Optimeta\Citations\Helpers;
use Optimeta\Citations\Model\AuthorModel;
use Optimeta\Shared\OpenAlex\OpenAlexBase;
use GuzzleHttp\Exception\GuzzleException;

class OpenAlex
{
    /**
     * @desc Get all information from OpenAlex and return as CitationModel
     * @param object $citation
     * @return object
     * @throws GuzzleException
     */
    public function getWork(object $citation): object
    {
        $doi = Helpers::removeDoiOrgPrefixFromUrl($citation->doi);
        $openAlex = new OpenAlexBase();
        $openAlexWork = $openAlex->getWorkFromApiAsObjectWithDoi($doi); // \Optimeta\Shared\OpenAlex\Model\Work()

        $citation->title = $openAlexWork->title;
        $citation->publication_year = $openAlexWork->publication_year;
        $citation->publication_date = $openAlexWork->publication_date;
        $citation->type = $openAlexWork->type;
        for($i = 0; $i < count((array)$openAlexWork->authorships); $i++){
            $author = new AuthorModel();
            $author->orcid = $openAlexWork->authorships[$i]['author']['orcid'];
            $author->name = $openAlexWork->authorships[$i]['author']['display_name'];
            $author->openalex_id = Helpers::removeOpenAlexOrgFromUrl($openAlexWork->authorships[$i]['author']['id']);
            $citation->authors[] = (array)$author;
        }
        $citation->cited_by_count = $openAlexWork->cited_by_count;

        if(!empty($openAlexWork->biblio['volume'])) $citation->volume = $openAlexWork->biblio['volume'];
        if(!empty($openAlexWork->biblio['issue'])) $citation->issue = $openAlexWork->biblio['issue'];
        $citation->pages = 0;
        if(!empty($openAlexWork->biblio['first_page'])) $citation->first_page = $openAlexWork->biblio['first_page'];
        if(!empty($openAlexWork->biblio['last_page'])) $citation->last_page = $openAlexWork->biblio['last_page'];

        $citation->is_retracted = $openAlexWork->is_retracted;
        $citation->updated_date = $openAlexWork->updated_date;
        $citation->created_date = $openAlexWork->created_date;

        if(!empty($openAlexWork->host_venue['issn_l'])) $citation->venue_issn_l = $openAlexWork->host_venue['issn_l'];
        if(!empty($openAlexWork->host_venue['display_name'])) $citation->venue_name = $openAlexWork->host_venue['display_name'];
        if(!empty($openAlexWork->host_venue['publisher'])) $citation->venue_publisher = $openAlexWork->host_venue['publisher'];
        if(!empty($openAlexWork->host_venue['is_oa'])) $citation->venue_is_oa = $openAlexWork->host_venue['is_oa'];
        if(!empty($openAlexWork->host_venue['id'])) $citation->venue_openalex_id = Helpers::removeOpenAlexOrgFromUrl($openAlexWork->host_venue['id']);

        $citation->openalex_id = Helpers::removeOpenAlexOrgFromUrl($openAlexWork->id);
        if(!empty($citation->openalex_id)) { $citation->isProcessed = true; }

        return $citation;
    }
}