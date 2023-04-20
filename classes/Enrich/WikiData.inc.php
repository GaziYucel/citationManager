<?php
namespace Optimeta\Citations\Enrich;

use Optimeta\Citations\Shared\Doi;
use Optimeta\Shared\WikiData\WikiDataBase;

class WikiData
{
    /**
     * @desc Get information from Wikidata and return as CitationModel
     * @param object $citation
     * @return object
     */
    public function getItem(object $citation): object
    {
        $objDoi = new Doi();
        $doi = $objDoi->removePrefixFromUrl($citation->doi);
        $wikiData = new WikiDataBase();
        $wikiDataQid = $wikiData->getEntity($doi);
        $doiWD = '';
        if(!empty($wikiDataQid)) { $doiWD = $wikiData->getDoi($wikiDataQid); }
        if(strtolower($doi) == strtolower($doiWD)) {
            $citation->wikidata_qid = $wikiDataQid; }

        return $citation;
    }
}