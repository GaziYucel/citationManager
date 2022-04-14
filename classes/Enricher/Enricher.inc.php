<?php
namespace Optimeta\Citations\Enricher;

import('plugins.generic.optimetaCitations.classes.Model.CitationModel');
import('plugins.generic.optimetaCitations.classes.Enricher.EnricherBase');

use Optimeta\Citations\Model\CitationModel;
use Optimeta\Shared\OpenAlex\OpenAlexBase;
use Optimeta\Shared\WikiData\WikiDataBase;

class Enricher extends EnricherBase
{
    /**
     * Returns parsed citations as an array
     *
     * @return array citationsEnriched
     */
    public function getCitationsEnrichedArray(): array
    {
        $this->enrich();

        return $this->citationsEnriched;
    }

    /**
     * Returns parsed citations as an JSON
     *
     * @return string (json) citationsEnriched
     */
    public function getCitationsEnrichedJson(): string
    {
        $this->enrich();

        if (sizeof($this->citationsEnriched) == 0) {
            return '[]';
        }

        return json_encode($this->citationsEnriched);
    }

    /**
     * Enrich and return enriched citations as an array
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function enrich(): void
    {
        // return if input is empty
        if (sizeof($this->citationsParsed) == 0) return;

        import('plugins.generic.optimetaCitations.classes.Debug');
//        $debug = new \Optimeta\Citations\Debug();
//        $debug->Clear();

        $wikiData = new WikiDataBase();
        $openAlex = new OpenAlexBase();

        // loop through citations and enrich every citation
        foreach ($this->citationsParsed as $index => $row) {
//            $debug->Add($row);

            if(is_object($row) || is_array($row)){
                // get data model and fill empty objRowParsed
                $objRowEnriched = new CitationModel();

                // convert array to object
                foreach($row as $key => $value){
                    $objRowEnriched->$key = $value;
                }

                $doiOri = str_replace('https://doi.org/', '', $objRowEnriched->doi);

                // WikiData QID
                $wikiDataQid = $wikiData->getEntity($doiOri);
                $doiExt = '';
                if(!empty($wikiDataQid)) $doiExt = $wikiData->getDoi($wikiDataQid);
                if(strtolower($doiOri) == strtolower($doiExt)) $objRowEnriched->wikidata_qid = $wikiDataQid;

                // OpenAlex ID
                $openAlexWorkId = $openAlex->getWorkId($doiOri);
                $objRowEnriched->openalex_id = $openAlexWorkId;

                // push to citations enriched array
                $this->citationsEnriched[] = (array)$objRowEnriched;
            }
        }
    }
}