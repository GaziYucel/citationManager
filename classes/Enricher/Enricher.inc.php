<?php
namespace Optimeta\Citations\Enricher;

import('plugins.generic.optimetaCitations.classes.Helpers');
import('plugins.generic.optimetaCitations.classes.Model.AuthorModel');
import('plugins.generic.optimetaCitations.classes.Model.CitationModel');
import('plugins.generic.optimetaCitations.classes.Enricher.OpenAlex');
import('plugins.generic.optimetaCitations.classes.Enricher.WikiData');

use Optimeta\Citations\Model\CitationModel;
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
                $objOpenAlex = new OpenAlex();
                $citation = $objOpenAlex->getOpenAlexWork($citation);

                // WikiData
                $objWikiData = new WikiData();
                $citation = $objWikiData->getWikiDataItem($citation);

                // push to citations enriched array
                $this->citationsEnriched[] = (array)$citation;
            }
        }
    }
}
