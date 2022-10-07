<?php
namespace Optimeta\Citations\VersionSpecific\Main\Dao;

import('lib.pkp.classes.db.DAO');
import('lib.pkp.classes.site.VersionCheck');

use DAO;
use DAOResultFactory;
use Optimeta\Citations\Dao\CitationsExtended;

class CitationsExtendedDAO extends \DAO
{
    /**
     * @desc Get CitationsExtended by Publication ID
     * @param $publicationId int Publication ID
     * @return DAOResultFactory
     */
    function getByPublicationId($publicationId) {
        $result = $this->retrieve(
            'SELECT * FROM citations_extended WHERE publication_id = ?',
            [(int) $publicationId]
        );
        return new DAOResultFactory($result, $this, '_fromRow', array('id'));
    }

    /**
     * @desc Get parsed citations by Publication ID
     * @param $publicationId int Publication ID
     * @return array
     * @see OJS version 3.2.1-x: classes/VersionSpecific/v321/CitationsExtendedDao
     */
    function getParsedCitationsByPublicationId($publicationId) {
        $citationsParsed = '';

        $result = $this->retrieve(
            'SELECT * FROM citations_extended WHERE publication_id = ?',
            [(int) $publicationId]
        );

        foreach ($result as $row) {
            $citationsParsed = $row->parsed_citations;
        }

        return $citationsParsed;
    }

    /**
     * @desc Checks if citationsParsed exists for this publication
     * @param $publicationId
     * @return bool
     */
    function doesParsedCitationsByPublicationIdExists($publicationId)
    {
        $result = $this->retrieve(
            'SELECT * FROM citations_extended WHERE publication_id = ?',
            [(int) $publicationId]
        );

        foreach ($result as $row) return true;

        return false;
    }

    /**
     * @desc Insert or update a CitationsExtended.
     * @param $citationsExtended CitationsExtended
     * @return int Inserted CitationsExtended ID or 0 if Updated
     */
    function insertOrUpdateObject($citationsExtended)
    {
        if($this->doesParsedCitationsByPublicationIdExists($citationsExtended->getPublicationId())){
            $this->updateObject($citationsExtended);
            return 0;
        }
        else{
            $this->insertObject($citationsExtended);
            return $citationsExtended->getId();
        }
    }

    /**
     * @desc Insert a CitationsExtended.
     * @param $citationsExtended CitationsExtended
     * @return int Inserted CitationsExtended ID
     */
    function insertObject($citationsExtended) {
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
     * @desc Update the database with a CitationsExtended object
     * @param $citationsExtended CitationsExtended
     */
    function updateObject($citationsExtended) {
        $this->update(
            'UPDATE	citations_extended SET parsed_citations = ? WHERE publication_id = ?',
            array(
                $citationsExtended->getParsedCitations(),
                $citationsExtended->getPublicationId()
            )
        );
    }

    /**
     * @desc Delete CitationsExtended by ID.
     * @param $citationsExtendedId int
     */
    function deleteById($citationsExtendedId) {
        $this->update(
            'DELETE FROM citations_extended WHERE citations_extended_id = ?',
            [(int) $citationsExtendedId]
        );
    }

    /**
     * @desc Delete a CitationsExtended object.
     * @param $citationsExtended CitationsExtended
     */
    function deleteObject($citationsExtended) {
        $this->deleteById($citationsExtended->getId());
    }

    /**
     * @desc Delete CitationsExtended by Publication ID
     * @param $publicationId int Publication ID
     */
    function deleteByPublicationId($publicationId) {
        $citationsExtended = $this->getByPublicationId($publicationId);
        while ($citationsExtended = $citationsExtended->next()) {
            $this->deleteObject($citationsExtended);
        }
    }

    /**
     * @desc Generate a new CitationsExtended object.
     * @return CitationsExtended
     */
    function newDataObject() {
        return new CitationsExtended();
    }

    /**
     * @desc Return a new CitationsExtended object from a given row.
     * @return CitationsExtended
     */
    function _fromRow($row) {
        $citationsExtended = $this->newDataObject();

        $citationsExtended->setId($row['citations_extended_id']);
        $citationsExtended->setPublicationId($row['publication_id']);
        $citationsExtended->setParsedCitations($row['parsed_citations']);

        return $citationsExtended;
    }

    /**
     * @desc Get the insert ID for the last inserted CitationsExtended.
     * @return int
     */
    function getInsertId() {
        return $this->_getInsertId('citations_extended', 'citations_extended_id');
    }

    /**
     * @desc Get the additional field names.
     * @return array
     */
    function getAdditionalFieldNames() {
        return array();
    }
}