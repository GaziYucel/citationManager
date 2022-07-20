<?php
/**
 * @desc Help methods for WorkModel
 */
namespace Optimeta\Citations\Model;

class WorkModelHelpers
{
    /**
     * @desc Migrates to current CitationModel
     * @param string $publicationWork
     * @return
     */
    public static function migrate(string $publicationWork)
    {
        if(empty($publicationWork) || !is_array(json_decode($publicationWork, true))) return (array)new WorkModel();

        $publicationWorkIn = json_decode($publicationWork, true);
        $publicationWorkOut = new WorkModel();

        foreach($publicationWorkOut as $index => $key){
            switch($key){
                case '-_-add case key here to do custom changes or mappings-_-':
                    break;
                default:
                    if(property_exists($publicationWorkIn, $key)){
                        $publicationWorkOut->$key = $publicationWorkIn->$key;
                    }
            }
        }

        error_log(json_encode($publicationWorkOut));

        return $publicationWorkOut;
    }

    /**
     * @desc Return WorkModel as an associative array with null values
     * @return array
     */
    public static function getModelAsArrayNullValues(): array
    {
        $workModel = [];

        foreach (new WorkModel() as $name => $value){
            $workModel[$name] = null;
        }

        return $workModel;
    }
}