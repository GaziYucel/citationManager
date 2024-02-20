<?php
/**
 * @file classes/Db/PluginDAO.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class PluginDAO
 * @brief DAO Schema
 */

namespace APP\plugins\generic\citationManager\classes\Db;

use APP\core\Services;
use APP\facades\Repo;
use APP\journal\JournalDAO;
use APP\plugins\generic\citationManager\CitationManagerPlugin;
use APP\plugins\generic\citationManager\classes\DataModels\Citation\CitationModel;
use APP\plugins\generic\citationManager\classes\DataModels\Metadata\AuthorMetadata;
use APP\plugins\generic\citationManager\classes\DataModels\Metadata\JournalMetadata;
use APP\plugins\generic\citationManager\classes\DataModels\Metadata\PublicationMetadata;
use APP\plugins\generic\citationManager\classes\Helpers\ClassHelper;
use APP\plugins\generic\citationManager\classes\Helpers\LogHelper;
use PKP\context\Context;

class PluginDAO
{
    /**
     * This method retrieves the structured citations for a publication.
     * After this, the method returns a normalized citations as an array of CitationModels.
     * If no citations are found, the method returns an empty array.
     *
     * @param int $publicationId
     * @return array
     */
    public function getCitations(int $publicationId): array
    {
        if (empty($publicationId))
            return [];

        $publication = Repo::publication()->get($publicationId);

        $fromDb =
            json_decode(
                $publication->getData(CitationManagerPlugin::CITATION_MANAGER_CITATIONS_STRUCTURED),
                true
            );

        if (empty($fromDb) || json_last_error() !== JSON_ERROR_NONE)
            return [];

        $result = [];

        foreach ($fromDb as $row) {
            if (!empty($row) && (is_object($row) || is_array($row))) {
                $result[] = ClassHelper::getClassAsArrayWithValuesAssigned(new CitationModel(), $row);
            }
        }

        return $result;
    }

    /**
     * This method saves the parsed citations for a publication.
     *
     * @param int $publicationId
     * @param array $citations
     * @return bool
     */
    public function saveCitations(int $publicationId, array $citations): bool
    {
        if (empty($publicationId))
            return false;

        $publication = Repo::publication()->get($publicationId);

        $publication->setData(
            CitationManagerPlugin::CITATION_MANAGER_CITATIONS_STRUCTURED,
            json_encode($citations)
        );

        Repo::publication()->dao->update($publication);

        return true;
    }

    /**
     * This method retrieves publicationWork for a publication and returns normalized to PublicationMetadata.
     * If nothing found, the method returns a new PublicationMetadata.
     *
     * @param int $publicationId
     * @return PublicationMetadata
     */
    public function getPublicationMetadata(int $publicationId): PublicationMetadata
    {
        if (empty($publicationId))
            return new PublicationMetadata();

        $publication = Repo::publication()->get($publicationId);

        $fromDb = json_decode(
            $publication->getData(CitationManagerPlugin::CITATION_MANAGER_METADATA_PUBLICATION),
            true
        );

        if (empty($fromDb) || json_last_error() !== JSON_ERROR_NONE)
            return new PublicationMetadata();

        return ClassHelper::getClassWithValuesAssigned(new PublicationMetadata(), $fromDb);
    }

    /**
     * This method saves publicationWork for a publication.
     *
     * @param int $publicationId
     * @param PublicationMetadata $publicationMetadata
     * @return bool
     */
    public function savePublicationMetadata(int $publicationId, PublicationMetadata $publicationMetadata): bool
    {
        if (empty($publicationId))
            return false;

        $publication = Repo::publication()->get($publicationId);

        $publication->setData(
            CitationManagerPlugin::CITATION_MANAGER_METADATA_PUBLICATION,
            json_encode($publicationMetadata)
        );

        Repo::publication()->dao->update($publication);

        return true;
    }

    /**
     * This method retrieves author metadata for an author and returns normalized.
     * If nothing found, the method returns a new AuthorMetadata.
     *
     * @param int $authorId
     * @return AuthorMetadata
     */
    public function getAuthorMetadata(int $authorId): AuthorMetadata
    {
        if (empty($authorId))
            return new AuthorMetadata();

        $author = Repo::author()->get($authorId);

        $fromDb = json_decode(
            $author->getData(CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHORS),
            true
        );

        if (empty($fromDb) || json_last_error() !== JSON_ERROR_NONE)
            return new AuthorMetadata();

        return ClassHelper::getClassWithValuesAssigned(new AuthorMetadata(), $fromDb);
    }

    /**
     * This method saves publicationWork for a publication.
     *
     * @param int $authorId
     * @param AuthorMetadata $authorMetadata
     * @return bool
     */
    public function saveAuthorMetadata(int $authorId, AuthorMetadata $authorMetadata): bool
    {
        if (empty($authorId))
            return false;

        $author = Repo::author()->get($authorId);

        $author->setData(
            CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHORS,
            json_encode($authorMetadata)
        );

        Repo::author()->dao->update($author);

        return true;
    }

    /**
     * This method retrieves JournalModel from publication_settings and returns normalized to JournalModel.
     * If nothing found, the method returns a new JournalModel.
     *
     * @param int $publicationId
     * @return JournalMetadata
     */
    public function getJournalMetadata(int $publicationId): JournalMetadata
    {
        if (empty($publicationId))
            return new JournalMetadata();

        $publication = Repo::publication()->get($publicationId);

        $fromDb = json_decode(
            $publication->getData(CitationManagerPlugin::CITATION_MANAGER_METADATA_JOURNAL),
            true
        );

        if (empty($fromDb) || json_last_error() !== JSON_ERROR_NONE)
            return new JournalMetadata();

        return ClassHelper::getClassWithValuesAssigned(new JournalMetadata(), $fromDb);
    }

    /**
     * This method saves JournalModel to publication_settings.
     *
     * @param int $publicationId
     * @param JournalMetadata $journalMetadata
     * @return bool
     */
    public function saveJournalMetadata(int $publicationId, JournalMetadata $journalMetadata): bool
    {
        if (empty($publicationId))
            return false;

        $publication = Repo::publication()->get($publicationId);

        $publication->setData(
            CitationManagerPlugin::CITATION_MANAGER_METADATA_JOURNAL,
            json_encode($journalMetadata)
        );

        Repo::publication()->dao->update($publication);

        return true;
    }

    /**
     * This method retrieves JournalModel for a context and returns normalized to JournalModel.
     * If nothing found, the method returns a new JournalModel.
     *
     * @param int $contextId
     * @return JournalMetadata
     */
    public function getJournalMetadata_does_not_work_in_ApiHandler(int $contextId): JournalMetadata
    {
        if (empty($contextId))
            return new JournalMetadata();

        /* @var Context $context */
        $context = Services::get('context')->get($contextId);

        $fromDb = json_decode(
            $context->getData(CitationManagerPlugin::CITATION_MANAGER_METADATA_JOURNAL),
            true
        );

        if (CitationManagerPlugin::isDebugMode) LogHelper::logInfo([$contextId, $fromDb]);

        if (empty($fromDb) || json_last_error() !== JSON_ERROR_NONE)
            return new JournalMetadata();

        return ClassHelper::getClassWithValuesAssigned(new JournalMetadata(), $fromDb);
    }

    /**
     * This method saves JournalModel for a context.
     *
     * @param int $contextId
     * @param JournalMetadata $journalMetadata
     * @return bool
     */
    public function saveJournalMetadata_does_not_work_in_ApiHandler(int $contextId, JournalMetadata $journalMetadata): bool
    {
        if (empty($contextId))
            return false;

        $contextDao = new JournalDAO();

        $context = $contextDao->getById($contextId);
        $context->setData(
            CitationManagerPlugin::CITATION_MANAGER_METADATA_JOURNAL,
            json_encode($journalMetadata)
        );

        $contextDao->updateObject($context);

        if (CitationManagerPlugin::isDebugMode) LogHelper::logInfo([$contextId, $context, $journalMetadata]);

        return true;
    }
}
