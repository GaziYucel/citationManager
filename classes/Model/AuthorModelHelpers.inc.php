<?php
/**
 * @desc Help methods for AuthorModel
 */
namespace Optimeta\Citations\Model;

class AuthorModelHelpers
{
    /**
     * @desc Return AuthorModel as an associative array with null values
     * @return array
     */
    public static function getModelAsArrayNullValues(): array
    {
        $workModel = [];

        foreach (new AuthorModel() as $name => $value){
            $workModel[$name] = null;
        }

        return $workModel;
    }
}