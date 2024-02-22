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

use APP\plugins\generic\citationManager\CitationManagerPlugin;
use APP\plugins\generic\citationManager\classes\DataModels\Citation\CitationModel;
use APP\plugins\generic\citationManager\classes\DataModels\Metadata\AuthorMetadata;
use APP\plugins\generic\citationManager\classes\DataModels\Metadata\JournalMetadata;
use APP\plugins\generic\citationManager\classes\DataModels\Metadata\PublicationMetadata;
use APP\plugins\generic\citationManager\classes\Helpers\ClassHelper;
use APP\plugins\generic\citationManager\classes\Helpers\LogHelper;
use Author;
use AuthorDAO;
use DAORegistry;
use Exception;
use Illuminate\Support\LazyCollection;
use Issue;
use IssueDAO;
use Journal;
use JournalDAO;
use Publication;
use PublicationDAO;
use Services;
use Submission;
use SubmissionDAO;

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
        /* @var IssueDAO $dao */
        $dao = DAORegistry::getDAO('IssueDAO');
        /* @var Issue */
        return $dao->getById($issueId);
    }
    public function getSubmission(int $submissionId): ?Submission
    {
        /* @var SubmissionDAO $dao */
        $dao = DAORegistry::getDAO('SubmissionDAO');
        /* @var Submission */
        return $dao->getById($submissionId);
    }
    public function getPublication(int $publicationId): ?Publication
    {
        /* @var PublicationDAO $dao */
        $dao = DAORegistry::getDAO('PublicationDAO');
        /* @var Publication */
        return $dao->getById($publicationId);
    }
    public function getAuthor(int $authorId): ?Author
    {
        /* @var AuthorDAO $dao */
        $dao = DAORegistry::getDAO('AuthorDAO');
        /* @var Author */
        return $dao->getById($authorId);
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
        /* @var IssueDAO $dao */
        $dao = DAORegistry::getDAO('IssueDAO');
        $dao->updateObject($issue);
    }
    public function saveSubmission(Submission $submission): void
    {
        /* @var SubmissionDAO $dao */
        $dao = DAORegistry::getDAO('SubmissionDAO');
        $dao->updateObject($submission);
    }
    public function savePublication(Publication $publication): void
    {
        /* @var PublicationDAO $dao */
        $dao = DAORegistry::getDAO('PublicationDAO');
        $dao->updateObject($publication);
    }
    public function saveAuthor(Author $author): void
    {
        /* @var AuthorDAO $dao */
        $dao = DAORegistry::getDAO('AuthorDAO');
        $dao->updateObject($author);
    }

    /**
     * Gets submissions for batch process
     *
     * @param int $contextId
     * @return LazyCollection
     */
    public function getBatchProcessSubmissions(int $contextId): LazyCollection
    {
        return Services::get('submission')->getMany([
            'contextId' => $contextId,
            'status' => STATUS_PUBLISHED]);
    }

    /**
     * Gets submissions for batch deposit
     *
     * @param int $contextId
     * @return LazyCollection
     */
    public function getBatchDepositSubmissions(int $contextId): LazyCollection
    {
        return Services::get('submission')->getMany([
            'contextId' => $contextId,
            'status' => STATUS_PUBLISHED]);
    }
}
