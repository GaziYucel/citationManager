<?php
/**
 * @file plugins/generic/optimetaCitations/Dao/CitationsExtendedDao.php
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

namespace APP\plugins\generic\optimetaCitations\classes\Db;

use APP\plugins\generic\optimetaCitations\classes\DataModels\CitationModel;
use PKP\db\DAO;
use PKP\db\DAOResultFactory;

class CitationsExtendedDAO extends DAO
{
    /**
     * Get CitationsExtended by Publication ID
     *
     * @param int $publicationId
     * @return DAOResultFactory
     */
    function getByPublicationId(int $publicationId): DAOResultFactory
    {
        $result = $this->retrieve(
            'SELECT * FROM citations_extended WHERE publication_id = ?',
            [$publicationId]
        );

        return new DAOResultFactory($result, $this, '_fromRow', array('id'));
    }

    /**
     * Get parsed citations by Publication ID
     *
     * @param $publicationId int Publication ID
     * @return ?string
     */
    function getParsedCitationsByPublicationId(int $publicationId): ?string
    {
        $citationsParsed = '';

        $result = $this->retrieve(
            'SELECT * FROM citations_extended WHERE publication_id = ?',
            [$publicationId]
        );

        foreach ($result as $row) {
            $citationsParsed = $row->parsed_citations;
        }

        return $citationsParsed;
    }

    /**
     * Checks if citationsParsed exists for this publication
     *
     * @param $publicationId int
     * @return bool
     */
    function doesParsedCitationsByPublicationIdExists(int $publicationId): bool
    {
        $result = $this->retrieve('SELECT * FROM citations_extended WHERE publication_id = ?',
            [$publicationId]);

        $row = $result->current();

        return (bool)$row;
    }

    /**
     * Insert or update a CitationsExtended.
     *
     * @param $citationsExtended CitationsExtended
     * @return int Inserted CitationsExtended ID or 0 if Updated
     */
    function insertOrUpdateObject(CitationsExtended $citationsExtended): int
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
     *
     * @param $citationsExtended CitationsExtended
     * @return int Inserted CitationsExtended ID
     */
    function insertObject(CitationsExtended $citationsExtended): int
    {
        $this->update('INSERT INTO citations_extended (publication_id, parsed_citations) VALUES (?, ?)',
            [$citationsExtended->getPublicationId(), $citationsExtended->getParsedCitations()]);

        $citationsExtended->setId($this->getInsertId());

        return $citationsExtended->getId();
    }

    /**
     * Update the database with a CitationsExtended object
     *
     * @param $citationsExtended CitationsExtended
     * @return void
     */
    function updateObject(CitationsExtended $citationsExtended): void
    {
        $this->update('UPDATE	citations_extended SET parsed_citations = ? WHERE publication_id = ?',
            [$citationsExtended->getParsedCitations(), $citationsExtended->getPublicationId()]);
    }

    /**
     * Delete CitationsExtended by ID.
     *
     * @param $citationsExtendedId int
     * @return void
     */
    function deleteById(int $citationsExtendedId): void
    {
        $this->update('DELETE FROM citations_extended WHERE citations_extended_id = ?',
            [$citationsExtendedId]);
    }

    /**
     * Delete a CitationsExtended object.
     *
     * @param $citationsExtended CitationsExtended
     * @return void
     */
    function deleteObject(CitationsExtended $citationsExtended): void
    {
        $this->deleteById($citationsExtended->getId());
    }

    /**
     * Delete CitationsExtended by Publication ID
     *
     * @param int $publicationId
     * @return void
     */
    function deleteByPublicationId(int $publicationId): void
    {
        try{
            $citationsExtended = $this->getByPublicationId($publicationId);
            while ($citationsExtended = $citationsExtended->next()) {
                $this->deleteObject($citationsExtended);
            }
        }
        catch(\Exception $ex){
            error_log($ex->getMessage());
        }
    }

    /**
     * Generate a new CitationsExtended object.
     *
     * @return CitationsExtended
     */
    function newDataObject(): CitationsExtended
    {
        return new CitationsExtended();
    }

    /**
     * Return a new CitationsExtended object from a given row.
     *
     * @param $row array
     * @return CitationsExtended
     */
    function _fromRow(array $row): CitationsExtended
    {
        $citationsExtended = $this->newDataObject();

        $citationsExtended->setId($row['citations_extended_id']);
        $citationsExtended->setPublicationId($row['publication_id']);
        $citationsExtended->setParsedCitations($row['parsed_citations']);

        return $citationsExtended;
    }

    /**
     * Get the insert ID for the last inserted CitationsExtended.
     *
     * @return int
     */
    function getInsertId(): int
    {
        return $this->_getInsertId();
    }

    /**
     * Get the additional field names.
     *
     * @return array
     */
    function getAdditionalFieldNames(): array
    {
        return array();
    }

    /**
     * Migrates to current CitationModel
     *
     * @param string $citations
     * @return array
     */
    public static function migrateCitations(string $citations): array
    {
        if (empty($citations) || !is_array(json_decode($citations, true))) {
            return [];
        }

        $citationsIn = json_decode($citations, true);
        $citationsOut = [];

        foreach ($citationsIn as $index => $row) {
            if (is_object($row) || is_array($row)) {
                $citation = new CitationModel();

                foreach ($row as $key => $value) {
                    switch ($key) {
                        case '-_-add key here to do custom changes or mappings-_-':
                            break;
                        default:
                            if (property_exists($citation, $key)) {
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
