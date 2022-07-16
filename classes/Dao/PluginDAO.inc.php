<?php
namespace Optimeta\Citations\Dao;

import('plugins.generic.optimetaCitations.classes.Model.CitationModelHelpers');

use Optimeta\Citations\Model\CitationModelHelpers;

class PluginDAO
{
    public function addToSchema(object $schema)
    {
        $schema->properties->{OPTIMETA_CITATIONS_PARSED_SETTING_NAME} = (object)[
            "type" => "string",
            "multilingual" => false,
            "apiSummary" => true,
            "validation" => ["nullable"]
        ];
    }

    public function saveCitations($publication, $citations)
    {
        $publication->setData(OPTIMETA_CITATIONS_PARSED_SETTING_NAME, $citations);
    }

    public function getCitations($publication): array
    {
        $citations = $publication->getData(OPTIMETA_CITATIONS_PARSED_SETTING_NAME);

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
        
        $publication->setData( OPTIMETA_CITATIONS_PARSED_SETTING_NAME . '_Count', $citationsCount);

        for($i = 0; $i < $citationsCount; $i++){
            $publication->setData(
                OPTIMETA_CITATIONS_PARSED_SETTING_NAME . $i,
                json_encode($citations[$i]));
        }

        $this->cleanUpCitations($publication, $citationsCount, $citationsCountBefore);

        return true;
    }

    public function getCitationsMultiRow($publication): array
    {
        $citations = '';

        if(!is_object($publication)) return [];

        $citationsCount = $this->getCitationsCountDb($publication);

        for($i = 0; $i < $citationsCount; $i++){
            $citations .= $publication->getData(OPTIMETA_CITATIONS_PARSED_SETTING_NAME . $i) . ',';
        }

        $citations = '[' . trim($citations, ',') . ']';

        return CitationModelHelpers::migrate($citations);
    }

    public function cleanUpCitations($publication, $citationsCount, $citationsCountBefore)
    {
        if($citationsCountBefore > $citationsCount){
            for($i = $citationsCount; $i < $citationsCountBefore; $i++){
                $publication->setData(
                    OPTIMETA_CITATIONS_PARSED_SETTING_NAME . $i,
                    null);
            }
        }
    }

    public function getCitationsCountDb($publication): int
    {
        $count = $publication->getData(OPTIMETA_CITATIONS_PARSED_SETTING_NAME . '_Count');
        if(empty($count)) $count = 0;
        return $count;
    }
}
