<?php
namespace Optimeta\Citations\Enricher;

import('plugins.generic.optimetaCitations.classes.Helpers');
import('plugins.generic.optimetaCitations.classes.Model.AuthorModel');
import('plugins.generic.optimetaCitations.classes.Model.CitationModel');

use Optimeta\Citations\Helpers;
use Optimeta\Citations\Model\AuthorModel;
use Optimeta\Citations\Model\CitationModel;
use Optimeta\Shared\OpenAlex\OpenAlexBase;
use GuzzleHttp\Exception\GuzzleException;

class Enricher
{
    /**
     * @desc Array which holds the parsed citations
     * @var array
     */
    protected $citationsParsed = [];

    /**
     * @desc Array which holds the enriched citations
     * @var array
     */
    protected $citationsEnriched = [];

    /**
     * @desc Constructor
     * @param array $citationsParsed parsed citations
     */
    function __construct(array $citationsParsed = [])
    {
        $this->citationsParsed = $citationsParsed;
    }

    /**
     * @desc Returns parsed citations as an array
     * @return array citationsEnriched
     * @throws GuzzleException
     */
    public function getCitations(): array
    {
        $this->execute();

        return $this->citationsEnriched;
    }

    /**
     * @desc Enrich citations and save results to citationsEnriched
     * @return void
     * @throws GuzzleException
     */
    private function execute(): void
    {
        // return if input is empty
        if (sizeof($this->citationsParsed) == 0) { return; }

        // loop through citations and enrich every citation
        foreach ($this->citationsParsed as $index => $row) {
            if(is_object($row) || is_array($row)){
                $citation = new CitationModel();

                // convert array to object
                foreach($row as $key => $value){
                    if(property_exists($citation, $key)){
                        $citation->$key = $value;
                    }
                }

                // skip iteration if isProcessed or DOI empty
                if($citation->isProcessed || empty($citation->doi)){
                    $this->citationsEnriched[] = (array)$citation;
                    continue;
                }

                // OpenAlex Work
                $citation = $this->getOpenAlex($citation);

                // push to citations enriched array
                $this->citationsEnriched[] = (array)$citation;
            }
        }
    }

    /**
     * @desc Get all information from OpenAlex and return as CitationModel
     * @param object $citation
     * @return object
     * @throws GuzzleException
     */
    private function getOpenAlex(object $citation): object
    {
        $openAlex = new OpenAlexBase(Helpers::removeDoiOrgPrefixFromUrl($citation->doi));
        $openAlexWork = $openAlex->getWorkFromApiAsObject(); // \Optimeta\Shared\OpenAlex\Model\Work()

        $citation->title = $openAlexWork->title;
        $citation->publication_year = $openAlexWork->publication_year;
        $citation->publication_date = $openAlexWork->publication_date;
        $citation->type = $openAlexWork->type;
        for($i = 0; $i < count((array)$openAlexWork->authorships); $i++){
            $author = new AuthorModel();
            $author->orcid = $openAlexWork->authorships[$i]['author']['orcid'];
            $author->display_name = $openAlexWork->authorships[$i]['author']['display_name'];
            $author->openalex_id = Helpers::removeOpenAlexOrgFromUrl($openAlexWork->authorships[$i]['author']['id']);
            $citation->authors[] = (array)$author;
        }
        $citation->cited_by_count = $openAlexWork->cited_by_count;
        $citation->volume = $openAlexWork->biblio['volume'];
        $citation->issue = $openAlexWork->biblio['issue'];
        $citation->pages = 0;
        $citation->first_page = $openAlexWork->biblio['first_page'];
        $citation->last_page = $openAlexWork->biblio['last_page'];
        $citation->is_retracted = $openAlexWork->is_retracted;
        $citation->updated_date = $openAlexWork->updated_date;
        $citation->created_date = $openAlexWork->created_date;
        $citation->venue_issn_l = $openAlexWork->host_venue['issn_l'];
        $citation->venue_display_name = $openAlexWork->host_venue['display_name'];
        $citation->venue_publisher = $openAlexWork->host_venue['publisher'];
        $citation->venue_is_oa = $openAlexWork->host_venue['is_oa'];
        $citation->venue_openalex_id = Helpers::removeOpenAlexOrgFromUrl($openAlexWork->host_venue['id']);
        $citation->openalex_id = Helpers::removeOpenAlexOrgFromUrl($openAlexWork->id);
        if(!empty($citation->openalex_id)) { $citation->isProcessed = true; }

        return $citation;
    }
}
