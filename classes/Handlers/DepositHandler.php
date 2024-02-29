<?php
/**
 * @file classes/Handlers/DepositHandler.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class DepositHandler
 * @brief Executes the deposit of publications and citations to external services.
 */

namespace APP\plugins\generic\citationManager\classes\Handlers;

use APP\author\Author;
use APP\facades\Repo;
use APP\plugins\generic\citationManager\CitationManagerPlugin;
use APP\plugins\generic\citationManager\classes\DataModels\Metadata\MetadataAuthor;
use APP\plugins\generic\citationManager\classes\DataModels\Metadata\MetadataJournal;
use APP\plugins\generic\citationManager\classes\DataModels\Metadata\MetadataPublication;
use APP\plugins\generic\citationManager\classes\Db\PluginDAO;
use APP\plugins\generic\citationManager\classes\External\OpenCitations\Deposit as OpenCitationsDeposit;
use APP\plugins\generic\citationManager\classes\External\Wikidata\Deposit as WikidataDeposit;
use APP\core\Application;
use APP\plugins\generic\citationManager\classes\Helpers\ClassHelper;
use APP\plugins\generic\citationManager\classes\PID\Orcid;
use APP\submission\Submission;
use Exception;
use Illuminate\Support\LazyCollection;
use PKP\plugins\PluginRegistry;

class DepositHandler
{
    /** @var CitationManagerPlugin */
    public CitationManagerPlugin $plugin;

    /** @var MetadataJournal|null */
    private ?MetadataJournal $metadataJournal = null;

    /** @var MetadataPublication|null */
    private ?MetadataPublication $metadataPublication = null;

    /** @var array|null [ { CitationModel }, ... ] */
    private ?array $citations = [];

    /** @var array|null */
    private ?array $authors = null;

    /** @var array|string[] */
    private array $services = [
        '\APP\plugins\generic\citationManager\classes\External\OpenCitations\Deposit',
        '\APP\plugins\generic\citationManager\classes\External\Wikidata\Deposit'
    ];

    public function __construct()
    {
        /** @var CitationManagerPlugin $plugin */
        $plugin = PluginRegistry::getPlugin('generic', strtolower(CITATION_MANAGER_PLUGIN_NAME));
        $this->plugin = $plugin;
    }

    /**
     * Deposit publication and citations to external services.
     *
     * @param string $submissionId The ID of the submission.
     * @param string $publicationId The ID of the publication.
     * @param MetadataPublication $metadataPublication The MetadataPublication of the publication.
     * @param array $citations Array of citations to be deposited.
     * @return bool
     */
    public function execute(string $submissionId,
                            string $publicationId,
                            array  $citations): bool
    {
        if (empty($submissionId) || empty($publicationId) || empty($citations)) return false;

        $pluginDao = new PluginDAO();
        $context = $this->plugin->getRequest()->getContext();
        $submission = $pluginDao->getSubmission($submissionId);
        $publication = $pluginDao->getPublication($publicationId);
        $issue = null;
        if (!empty($publication->getData('issueId')))
            $issue = $pluginDao->getIssue($publication->getData('issueId'));
        $this->metadataJournal = $pluginDao->getMetadataJournal($context->getId());
        $this->metadataPublication = $pluginDao->getMetadataPublication($publicationId);
        $this->citations = $citations;

        if (empty($publication->getStoredPubId('doi')) || empty($issue)) return false;

        // author(s)
        foreach ($publication->getData('authors') as $id => $author) {
            /* @var Author $author */
            $metadataAuthor = $author->getData(CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHOR);
            if (empty($metadataAuthor)) {
                $author->setData(CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHOR, new MetadataAuthor());
            }
            $this->authors[] = $author;
        }

        // iterate services
        foreach ($this->services as $serviceClass) {
            $service = new $serviceClass ($this->plugin, $context, $issue, $submission, $publication,
                $this->metadataJournal, $this->metadataPublication, $this->authors, $this->citations);

            $service->execute();

            $this->metadataJournal = $service->getMetadataJournal();
            $this->metadataPublication = $service->getMetadataPublication();
            $this->authors = $service->getAuthors();
            $this->citations = $service->getCitations();
        }

        // save to database
        $pluginDao->saveMetadataJournal($context->getId(), $this->metadataJournal);
        $pluginDao->saveMetadataPublication($publicationId, $this->metadataPublication);
        $pluginDao->saveCitations($publicationId, $this->citations);
        /* @var Author $author */
        foreach ($this->authors as $id => $author) {
            $pluginDao->saveMetadataAuthor(
                $author->getId(),
                $author->getData(CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHOR));
        }

        return true;
    }

    /**
     * Perform batch deposit for all contexts and submissions.
     *
     * @return bool True if the batch deposit is successful, false otherwise.
     */
    public function batchExecute(): bool
    {
        $contextIds = [];

        $contextDao = Application::getContextDAO();
        $contextFactory = $contextDao->getAll();
        try {
            while ($context = $contextFactory->next()) {
                $contextIds[] = $context->getId();
            }
        } catch (Exception $e) {
            error_log(__METHOD__ . ' ' . $e->getMessage());
        }

        $pluginDao = new PluginDAO();

        foreach ($contextIds as $contextId) {

            $submissions = Repo::submission()->getCollector()
                ->filterByContextIds([$contextId])
                ->filterByStatus([Submission::STATUS_PUBLISHED]);

            foreach ($submissions as $submission) {

                $publications = $submission->getPublishedPublications();

                foreach ($publications as $publication) {

                    $this->metadataPublication = new MetadataPublication();
                    $this->citations = [];

                    $this->execute(
                        $submission->getId(),
                        $publication->getId(),
                        $pluginDao->getCitations($publication->getId()));
                }
            }
        }

        return true;
    }

    // region getters
    public function getMetadataJournal(): MetadataJournal
    {
        return $this->metadataJournal;
    }
    public function getMetadataPublication(): MetadataPublication
    {
        return $this->metadataPublication;
    }
    public function getCitations(): array
    {
        return $this->citations;
    }
    public function getAuthors(): array
    {
        return $this->authors;
    }
    // endregion
}
