<?php
namespace Optimeta\Citations\Submitter;

import('plugins.generic.optimetaCitations.classes.Helpers');
import('plugins.generic.optimetaCitations.classes.Model.AuthorModel');
import('plugins.generic.optimetaCitations.classes.Model.CitationModel');

use Optimeta\Citations\Helpers;
use Optimeta\Citations\Model\AuthorModel;
use Optimeta\Citations\Model\CitationModel;
use Optimeta\Shared\WikiData\WikiDataBase;
use GuzzleHttp\Exception\GuzzleException;

class Submitter
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
     * @desc Array which holds the enriched citations
     * @var array
     */
    protected $citationsSubmitted = [];

    /**
     * @desc Constructor
     * @param array $citationsParsed parsed citations
     */
    function __construct(array $citationsParsed = [])
    {
        $this->citationsParsed = $citationsParsed;
    }

    /**
     * @desc Returns submitted citations as an array
     * @return array citationsSubmitted
     * @throws GuzzleException
     */
    public function getCitations(): array
    {
        $this->execute();

        return $this->citationsSubmitted;
    }

    /**
     * @desc Submit enriched citations and save to citationsSubmitted
     * @return void
     * @throws GuzzleException
     */
    public function execute(): void
    {
        // return if input is empty
        if (sizeof($this->citationsParsed) == 0) { return; }

        // loop through citations and submit enriched citations
        foreach ($this->citationsParsed as $index => $row) {
            if(is_object($row) || is_array($row)){
                $citation = new CitationModel();

                // convert array to object
                foreach($row as $key => $value){
                    if(property_exists($citation, $key)){
                        $citation->$key = $value;
                    }
                }

                // Wikidata
                $citation = $this->getWikiData($citation);

                // push to citations enriched array
                $this->citationsSubmitted[] = (array)$citation;
            }
        }
    }

    /**
     * @desc Get information from Wikidata and return as CitationModel
     * @param object $citation
     * @return object
     * @throws GuzzleException
     */
    private function getWikiData(object $citation): object
    {
        $wikiData = new WikiDataBase();
        $wikiDataQid = $wikiData->getEntity(Helpers::removeDoiOrgPrefixFromUrl($citation->doi));
        $doiWD = '';
        if(!empty($wikiDataQid)) { $doiWD = $wikiData->getDoi($wikiDataQid); }
        if(strtolower(Helpers::removeDoiOrgPrefixFromUrl($citation->doi)) == strtolower($doiWD)) {
            $citation->wikidata_qid = $wikiDataQid; }

        return $citation;
    }
}