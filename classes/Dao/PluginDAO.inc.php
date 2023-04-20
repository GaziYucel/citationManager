<?php
namespace Optimeta\Citations\Dao;

use DAORegistry;
use Optimeta\Citations\Model\CitationModel;

class PluginDAO
{
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

    public function getCitations($publication)
    {
        $citationsExtendedDAO = DAORegistry::getDAO('CitationsExtendedDAO');
        $citations = $citationsExtendedDAO->getParsedCitationsByPublicationId($publication->getId());

        if(empty($citations)) $citations = '[]';

        $citationModel = new CitationModel();

        return $citationModel->migrate($citations);
    }

    public function saveCitations($publication, $citations)
    {
        $citationsExtendedDAO = DAORegistry::getDAO('CitationsExtendedDAO');
        $citationsExtended = $citationsExtendedDAO->newDataObject();
        $citationsExtended->setPublicationId($publication->getId());
        $citationsExtended->setParsedCitations($citations);
        $citationsExtendedDAO->insertOrUpdateObject($citationsExtended);
    }
}
