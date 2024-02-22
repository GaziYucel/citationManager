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

use APP\facades\Repo;
use APP\plugins\generic\citationManager\CitationManagerPlugin;
use APP\plugins\generic\citationManager\classes\DataModels\Citation\CitationModel;
use APP\plugins\generic\citationManager\classes\DataModels\Metadata\AuthorMetadata;
use APP\plugins\generic\citationManager\classes\DataModels\Metadata\JournalMetadata;
use APP\plugins\generic\citationManager\classes\DataModels\Metadata\PublicationMetadata;
use APP\plugins\generic\citationManager\classes\Helpers\ClassHelper;
use APP\plugins\generic\citationManager\classes\Helpers\LogHelper;
use APP\submission\Collector;
use Author;
use DAORegistry;
use Exception;
use Issue;
use Journal;
use JournalDAO;
use Publication;
use Services;
use Submission;

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

        $publication = $this->getPublication($publicationId);

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

        $publication = $this->getPublication($publicationId);

        $publication->setData(
            CitationManagerPlugin::CITATION_MANAGER_CITATIONS_STRUCTURED,
            json_encode($citations)
        );

        $this->savePublication($publication);

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

        $publication = $this->getPublication($publicationId);

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

        $publication = $this->getPublication($publicationId);

        $publication->setData(
            CitationManagerPlugin::CITATION_MANAGER_METADATA_PUBLICATION,
            json_encode($publicationMetadata)
        );

        $this->savePublication($publication);

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

        $author = $this->getAuthor($authorId);

        $fromDb = json_decode(
            $author->getData(CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHOR),
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

        $author = $this->getAuthor($authorId);

        $author->setData(
            CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHOR,
            json_encode($authorMetadata)
        );

        $this->saveAuthor($author);

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

        $publication = $this->getPublication($publicationId);

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

        $publication = $this->getPublication($publicationId);

        $publication->setData(
            CitationManagerPlugin::CITATION_MANAGER_METADATA_JOURNAL,
            json_encode($journalMetadata)
        );

        $this->savePublication($publication);

        return true;
    }

    /* OJS getters */
    public function getJournal(int $journalId): ?Journal
    {
        /* @var JournalDAO $dao */
        $dao = DAORegistry::getDAO('JournalDAO');
        /* @var Journal */
        return $dao->getById($journalId);
    }
    public function getIssue(int $issueId): ?Issue
    {
        return Repo::issue()->get($issueId);
    }
    public function getSubmission(int $submissionId): ?Submission
    {
        return Repo::submission()->get($submissionId);
    }
    public function getPublication(int $publicationId): ?Publication
    {
        return Repo::publication()->get($publicationId);
    }
    public function getAuthor(int $authorId): ?Author
    {
        return Repo::author()->get($authorId);
    }

    /* OJS setters */
    public function saveJournal(Journal $journal): void
    {
        /* @var JournalDAO $dao */
        $dao = DAORegistry::getDAO('JournalDAO');
        $dao->updateObject($journal);
    }
    public function saveIssue(Issue $issue): void
    {
        Repo::issue()->dao->update($issue);
    }
    public function saveSubmission(Submission $submission): void
    {
        Repo::submission()->dao->update($submission);
    }
    public function savePublication(Publication $publication): void
    {
        Repo::publication()->dao->update($publication);
    }
    public function saveAuthor(Author $author): void
    {
        Repo::author()->dao->update($author);
    }

    /**
     * Gets submissions for batch process
     *
     * @param int $contextId
     * @return Collector
     */
    public function getBatchProcessSubmissions(int $contextId): Collector
    {
        return Repo::submission()->getCollector()->filterByContextIds([$contextId]);
    }

    /**
     * Gets submissions for batch deposit
     *
     * @param int $contextId
     * @return Collector
     */
    public function getBatchDepositSubmissions(int $contextId): Collector
    {
        return Repo::submission()->getCollector()
            ->filterByContextIds([$contextId])
            ->filterByStatus([Submission::STATUS_PUBLISHED]);
    }
}
