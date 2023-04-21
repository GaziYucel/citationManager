<?php
/**
 * @file plugins/generic/optimetaCitations/Dao/CitationsExtended.inc.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class CitationsExtended
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief DataObject  for get/set PublicationId and ParsedCitations
 */

namespace Optimeta\Citations\Dao;

use DataObject;

class CitationsExtended extends DataObject
{
    /**
     * @desc Get Publication ID
     * @return int
     */
    function getPublicationId()
    {
        return $this->getData('publicationId');
    }

    /**
     * @desc Set Publication ID
     * @param $publicationId int
     */
    function setPublicationId($publicationId)
    {
        return $this->setData('publicationId', $publicationId);
    }

    /**
     * @desc Get ParsedCitations
     * @return string
     */
    function getParsedCitations()
    {
        return $this->getData('parsedCitations');
    }

    /**
     * @desc Set ParsedCitations
     * @param $parsedCitations string
     */
    function setParsedCitations($parsedCitations)
    {
        return $this->setData('parsedCitations', $parsedCitations);
    }
}