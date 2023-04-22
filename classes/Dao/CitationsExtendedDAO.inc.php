<?php
/**
 * @file plugins/generic/optimetaCitations/Dao/CitationsExtendedDao.inc.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class CitationsExtendedDAO
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief DAO for get/set PublicationId and ParsedCitations
 */

namespace Optimeta\Citations\Dao;

import('lib.pkp.classes.db.DAO');
import('lib.pkp.classes.site.VersionCheck');

use DAO;
use DAOResultFactory;

class CitationsExtendedDAO extends DAO
{
    /**
     * Get CitationsExtended by Publication ID
     * @param $publicationId int Publication ID
     * @return array
     */
    function getByPublicationId($publicationId)
    {
        $result = $this->retrieve(
            'SELECT * FROM citations_extended WHERE publication_id = ?',
            [(int)$publicationId]
        );
        return new DAOResultFactory($result, $this, '_fromRow', array('id'));
    }

    /**
     * Get parsed citations by Publication ID
     * @param $publicationId int Publication ID
     * @return string
     */
    function getParsedCitationsByPublicationId($publicationId)
    {
        $citationsParsed = '';

        $result = $this->retrieve(
            'SELECT * FROM citations_extended WHERE publication_id = ?',
            [(int)$publicationId]
        );

        foreach ($result as $row) {
            $citationsParsed = $row->parsed_citations;
        }

        return $citationsParsed;
    }

    /**
     * Checks if citationsParsed exists for this publication
     * @param $publicationId int
     * @return bool
     */
    function doesParsedCitationsByPublicationIdExists($publicationId)
    {
        $result = $this->retrieve(
            'SELECT * FROM citations_extended WHERE publication_id = ?',
            [(int)$publicationId]
        );

        foreach ($result as $row) return true;

        return false;
    }

    /**
     * Insert or update a CitationsExtended.
     * @param $citationsExtended CitationsExtended
     * @return int Inserted CitationsExtended ID or 0 if Updated
     */
    function insertOrUpdateObject($citationsExtended)
    {
        if ($this->doesParsedCitationsByPublicationIdExists($citationsExtended->getPublicationId())) {
            $this->updateObject($citationsExtended);
            return 0;
        } else {
            $this->insertObject($citationsExtended);
            return $citationsExtended->getId();
        }
    }

    /**
     * Insert a CitationsExtended.
     * @param $citationsExtended CitationsExtended
     * @return int Inserted CitationsExtended ID
     */
    function insertObject($citationsExtended)
    {
        $this->update(
            'INSERT INTO citations_extended (publication_id, parsed_citations) VALUES (?, ?)',
            array(
                $citationsExtended->getPublicationId(),
                $citationsExtended->getParsedCitations()
            )
        );

        $citationsExtended->setId($this->getInsertId());

        return $citationsExtended->getId();
    }

    /**
     * Update the database with a CitationsExtended object
     * @param $citationsExtended CitationsExtended
     * @return void
     */
    function updateObject($citationsExtended)
    {
        $this->update(
            'UPDATE	citations_extended SET parsed_citations = ? WHERE publication_id = ?',
            array(
                $citationsExtended->getParsedCitations(),
                $citationsExtended->getPublicationId()
            )
        );
    }

    /**
     * Delete CitationsExtended by ID.
     * @param $citationsExtendedId int
     * @return void
     */
    function deleteById($citationsExtendedId)
    {
        $this->update(
            'DELETE FROM citations_extended WHERE citations_extended_id = ?',
            [(int)$citationsExtendedId]
        );
    }

    /**
     * Delete a CitationsExtended object.
     * @param $citationsExtended CitationsExtended
     * @return void
     */
    function deleteObject($citationsExtended)
    {
        $this->deleteById($citationsExtended->getId());
    }

    /**
     * Delete CitationsExtended by Publication ID
     * @param $publicationId int Publication ID
     * @return void
     */
    function deleteByPublicationId($publicationId)
    {
        $citationsExtended = $this->getByPublicationId($publicationId);
        while ($citationsExtended = $citationsExtended->next()) {
            $this->deleteObject($citationsExtended);
        }
    }

    /**
     * Generate a new CitationsExtended object.
     * @return CitationsExtended
     */
    function newDataObject()
    {
        return new CitationsExtended();
    }

    /**
     * Return a new CitationsExtended object from a given row.
     * @param $row array
     * @return CitationsExtended
     */
    function _fromRow($row)
    {
        $citationsExtended = $this->newDataObject();

        $citationsExtended->setId($row['citations_extended_id']);
        $citationsExtended->setPublicationId($row['publication_id']);
        $citationsExtended->setParsedCitations($row['parsed_citations']);

        return $citationsExtended;
    }

    /**
     * Get the insert ID for the last inserted CitationsExtended.
     * @return int
     */
    function getInsertId()
    {
        return $this->_getInsertId('citations_extended', 'citations_extended_id');
    }

    /**
     * Get the additional field names.
     * @return array
     */
    function getAdditionalFieldNames()
    {
        return array();
    }
}
