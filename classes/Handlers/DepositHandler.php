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

use APP\plugins\generic\citationManager\CitationManagerPlugin;
use APP\plugins\generic\citationManager\classes\DataModels\Metadata\MetadataJournal;
use APP\plugins\generic\citationManager\classes\DataModels\Metadata\MetadataPublication;
use APP\plugins\generic\citationManager\classes\Db\PluginDAO;
use APP\plugins\generic\citationManager\classes\External\OpenCitations\Deposit as OpenCitationsDeposit;
use APP\plugins\generic\citationManager\classes\External\Wikidata\Deposit as WikidataDeposit;
use Application;
use Exception;
use PluginRegistry;
use Services;

class DepositHandler
{
    /** @var CitationManagerPlugin */
    protected CitationManagerPlugin $plugin;

    /** @var MetadataJournal|null */
    private ?MetadataJournal $metadataPublication = null;

    /** @var MetadataPublication|null */
    private ?MetadataPublication $metadataPublication = null;

    /** @var array|null */
    private ?array $citations = null;

    /** @var array|null */
    private ?array $authors = [];

    public function __construct()
    {
        /** @var CitationManagerPlugin $plugin */
        $plugin = PluginRegistry::getPlugin('generic',  strtolower(CITATION_MANAGER_PLUGIN_NAME));
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
    public function execute(string              $submissionId,
                            string              $publicationId,
                            MetadataPublication $metadataPublication,
                            array               $citations): bool
    {
        $pluginDao = new PluginDAO();
	
        $publication = $pluginDao->getPublication($publicationId);
        $issue = $pluginDao->getIssue($publication->getData('issueId'));
        $locale = $publication->getData('locale');

        if (empty($submissionId) || empty($publicationId) || empty($citations) ||
            empty($publication->getStoredPubId('doi')) || empty($locale) || empty($issue)) {
            return false;
        }

        $context = $this->plugin->getRequest()->getContext();
        $submission = $pluginDao->getSubmission($submissionId);

        // OpenCitations
        $depositOC = new OpenCitationsDeposit($this->plugin);
        $depositOC->execute(
            $context,
            $issue,
            $submission,
            $publication,
            $metadataPublication,
            $citations
        );
        $this->metadataPublication = $depositOC->getMetadataPublication();
        $this->citations = $depositOC->getCitations();

        // Wikidata
        $depositWD = new WikidataDeposit($this->plugin);
        $depositWD->execute(
            $context,
            $issue,
            $submission,
            $publication,
            $metadataPublication,
            $citations
        );
        $this->metadataPublication = $depositWD->getMetadataPublication();
        $this->citations = $depositWD->getCitations();
        $this->authors = $depositWD->getAuthors();

        // save to database
        $pluginDao = new PluginDAO();
        $pluginDao->saveMetadataPublication($publicationId, $this->metadataPublication);
        $pluginDao->saveCitations($publicationId, $this->citations);

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

            $submissions = Services::get('submission')->getMany([
                'contextId' => $contextId,
                'status' => STATUS_PUBLISHED]);

            foreach ($submissions as $submission) {

                $publications = $submission->getPublishedPublications();

                foreach ($publications as $publication) {

                    $this->metadataPublication = new MetadataPublication();
                    $this->citations = [];

                    $this->execute(
                        $submission->getId(),
                        $publication->getId(),
                        $pluginDao->getMetadataPublication($publication->getId()),
                        $pluginDao->getCitations($publication->getId()));
                }
            }
        }

        return true;
    }

    /**
     * Return publication metadata
     *
     * @return MetadataPublication
     */
    public function getMetadataPublication(): MetadataPublication
    {
        if (empty($this->metadataPublication))
            return new MetadataPublication();

        return $this->metadataPublication;
    }

    /**
     * Return citations
     *
     * @return array
     */
    public function getCitations(): array
    {
        return $this->citations;
    }

    /**
     * Return publication authors
     *
     * @return array
     */
    public function getAuthors(): array
    {
        if (empty($this->authors)) return [];

        return $this->authors;
    }
}
