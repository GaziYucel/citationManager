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
    public function getWikiDataItem(object $citation): object
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