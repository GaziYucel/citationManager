<?php
namespace Optimeta\Citations\Dao;

import('plugins.generic.optimetaCitations.classes.Model.CitationModelHelpers');

use Optimeta\Citations\Model\CitationModelHelpers;

class PluginDAO
{
    public function addToSchema(object $schema)
    {
        $schema->properties->{OPTIMETA_CITATIONS_PARSED_KEY_DB} = (object)[
            "type" => "string",
            "multilingual" => false,
            "apiSummary" => true,
            "validation" => ["nullable"]
        ];
    }

    public function saveCitations($publication, $citations)
    {
        $publication->setData(OPTIMETA_CITATIONS_PARSED_KEY_DB, $citations);
    }

    public function getCitations($publication): array
    {
        $citations = $publication->getData(OPTIMETA_CITATIONS_PARSED_KEY_DB);

        if(empty($citations)) $citations = [];

        return CitationModelHelpers::migrate($citations);
    }

    /* Citations saved in multiple rows */
    public function saveCitationsMultiRow($publication, $citationsJson): bool
    {
        if(!is_object($publication) || empty($citationsJson) || !is_array(json_decode($citationsJson, true))){
            $citationsJson = '[]';
        }

        $citations = json_decode($citationsJson, true);
        $citationsCount = count($citations);
        $citationsCountBefore = $this->getCitationsCountDb($publication);

        $publication->setData( OPTIMETA_CITATIONS_PARSED_KEY_DB_COUNT, $citationsCount);

        for($i = 0; $i < $citationsCount; $i++){
            $publication->setData(
                OPTIMETA_CITATIONS_PARSED_KEY_DB . $i,
                json_encode($citations[$i]));
        }

        $this->cleanUpCitations($publication, $citationsCount, $citationsCountBefore);

        return true;
    }

    public function getCitationsMultiRow(object $publication): array
    {
        $citations = '';

        if(!is_object($publication)) return [];

        $citationsCount = $this->getCitationsCountDb($publication);

        for($i = 0; $i < $citationsCount; $i++){
            $citations .= $publication->getData(OPTIMETA_CITATIONS_PARSED_KEY_DB . $i) . ',';
        }

        $citations = '[' . trim($citations, ',') . ']';

        return CitationModelHelpers::migrate($citations);
    }

    public function cleanUpCitations(object $publication, int $citationsCount, int $citationsCountBefore)
    {
        if($citationsCountBefore > $citationsCount){
            for($i = $citationsCount; $i < $citationsCountBefore; $i++){
                $publication->setData(
                    OPTIMETA_CITATIONS_PARSED_KEY_DB . $i,
                    null);
            }
        }
    }

    public function getCitationsCountDb(object $publication): int
    {
        $count = $publication->getData(OPTIMETA_CITATIONS_PARSED_KEY_DB_COUNT);
        if(empty($count)) $count = 0;
        return $count;
    }
}
