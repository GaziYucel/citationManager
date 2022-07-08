<?php
namespace Optimeta\Citations\Enricher;

import('plugins.generic.optimetaCitations.classes.Helpers');

use Optimeta\Citations\Helpers;
use Optimeta\Shared\WikiData\WikiDataBase;
use GuzzleHttp\Exception\GuzzleException;

class WikiData
{
    /**
     * @desc Get information from Wikidata and return as CitationModel
     * @param object $citation
     * @return object
     * @throws GuzzleException
     */
    public function getItem(object $citation): object
    {
        $doi = Helpers::removeDoiOrgPrefixFromUrl($citation->doi);
        $wikiData = new WikiDataBase();
        $wikiDataQid = $wikiData->getEntity($doi);
        $doiWD = '';
        if(!empty($wikiDataQid)) { $doiWD = $wikiData->getDoi($wikiDataQid); }
        if(strtolower($doi) == strtolower($doiWD)) {
            $citation->wikidata_qid = $wikiDataQid; }

        return $citation;
    }
}