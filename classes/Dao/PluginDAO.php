<?php
/**
 * @file plugins/generic/optimetaCitations/Dao/PluginDAO.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class PluginDAO
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief DAO Schema
 */

namespace APP\plugins\generic\optimetaCitations\classes\Dao;

use DAORegistry;
use APP\plugins\generic\optimetaCitations\classes\Model\CitationModel;

class PluginDAO
{
    /**
     * This method adds two properties to the schema of a publication:
     * OPTIMETA_CITATIONS_FORM_FIELD_PARSED and OPTIMETA_CITATIONS_PUBLICATION_WORK.
     * These properties are used to store information related to the citations for the publication.
     * @param object $schema
     * @return void
     */
    public function addToSchema(object $schema)
    {
        $schema->properties->{OPTIMETA_CITATIONS_FORM_FIELD_PARSED} = (object)[
            "type" => "string",
            "multilingual" => false,
            "apiSummary" => true,
            "validation" => ["nullable"]
        ];

        $schema->properties->{OPTIMETA_CITATIONS_PUBLICATION_WORK} = (object)[
            "type" => "string",
            "multilingual" => false,
            "apiSummary" => true,
            "validation" => ["nullable"]
        ];
    }

    /**
     * This method retrieves the parsed citations for a publication.
     * It uses the CitationsExtendedDAO class to retrieve the parsed citations by the publication ID.
     * If no parsed citations are found, the method returns an empty array. Otherwise,
     * it creates a new CitationModel object and uses its migrate() method to convert the parsed
     * citations into an array of citation objects.
     * @param $publication
     * @return array
     */
    public function getCitations($publication)
    {
        $citationsExtendedDAO = DAORegistry::getDAO('CitationsExtendedDAO');
        $citations = $citationsExtendedDAO->getParsedCitationsByPublicationId($publication->getId());

        if (empty($citations)) $citations = '[]';

        $citationModel = new CitationModel();

        return $citationModel->migrate($citations);
    }

    /**
     * This method saves the parsed citations for a publication. It uses the CitationsExtendedDAO
     * class to create a new CitationsExtended object and sets the publication ID and parsed
     * citations on it. Finally, it uses the insertOrUpdateObject() method of the
     * CitationsExtendedDAO class to insert or update the
     * object in the database.
     * @param $publication
     * @param $citations
     * @return void
     */
    public function saveCitations($publication, $citations)
    {
        $citationsExtendedDAO = DAORegistry::getDAO('CitationsExtendedDAO');
        $citationsExtended = $citationsExtendedDAO->newDataObject();
        $citationsExtended->setPublicationId($publication->getId());
        $citationsExtended->setParsedCitations($citations);
        $citationsExtendedDAO->insertOrUpdateObject($citationsExtended);
    }
}
