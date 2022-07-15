<?php
/**
 * @desc Help methods for CitationModel
 */
namespace Optimeta\Citations\Model;

class CitationModelHelpers
{
    /**
     * @desc Migrates to current CitationModel
     * @param string $citations
     * @return array
     */
    public static function migrate(string $citations): array
    {
        if(empty($citations) || !is_array(json_decode($citations, true))) return [];

        $citationsIn = json_decode($citations, true);
        $citationsOut = [];

        foreach ($citationsIn as $index => $row) {
            if(is_object($row) || is_array($row)){
                $citation = new CitationModel();

                foreach($row as $key => $value){
                    switch($key){
                        case '-_-add case key here to do custom changes or mappings-_-':
                            break;
                        default:
                            if(property_exists($citation, $key)){
                                $citation->$key = $value;
                            }
                    }
                }

                $citationsOut[] = (array)$citation;
            }
        }

        return $citationsOut;
    }
}