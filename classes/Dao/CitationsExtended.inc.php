<?php
namespace Optimeta\Citations\Dao;

use DataObject;

class CitationsExtended extends DataObject
{
    /**
     * @desc Get Publication ID
     * @return int
     */
    function getPublicationId(){
        return $this->getData('publicationId');
    }

    /**
     * @desc Set Publication ID
     * @param $publicationId int
     */
    function setPublicationId($publicationId) {
        return $this->setData('publicationId', $publicationId);
    }

    /**
     * @desc Get ParsedCitations
     * @return string
     */
    function getParsedCitations() {
        return $this->getData('parsedCitations');
    }

    /**
     * @desc Set ParsedCitations
     * @param $parsedCitations string
     */
    function setParsedCitations($parsedCitations) {
        return $this->setData('parsedCitations', $parsedCitations);
    }
}