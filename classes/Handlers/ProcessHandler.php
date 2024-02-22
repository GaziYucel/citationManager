<?php
/**
 * @file classes/Models/ProcessHandler.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class ProcessHandler
 * @brief Executes cleaning, splitting, extracting PID's, structuring and enriching citations
 */

namespace APP\plugins\generic\citationManager\classes\Handlers;

use APP\plugins\generic\citationManager\CitationManagerPlugin;
use APP\plugins\generic\citationManager\classes\DataModels\Citation\CitationModel;
use APP\plugins\generic\citationManager\classes\Db\PluginDAO;
use APP\plugins\generic\citationManager\classes\External\OpenAlex\Enrich as OpenAlexEnrich;
use APP\plugins\generic\citationManager\classes\External\Orcid\Enrich as OrcidEnrich;
use APP\plugins\generic\citationManager\classes\External\Wikidata\Enrich as WikidataEnrich;
use APP\plugins\generic\citationManager\classes\Helpers\StringHelper;
use APP\plugins\generic\citationManager\classes\PID\Arxiv;
use APP\plugins\generic\citationManager\classes\PID\Doi;
use APP\plugins\generic\citationManager\classes\PID\Handle;
use APP\plugins\generic\citationManager\classes\PID\Url;
use APP\plugins\generic\citationManager\classes\PID\Urn;
use Application;
use Publication;
use Submission;
use Exception;

class ProcessHandler
{
    /** @var CitationManagerPlugin */
    public CitationManagerPlugin $plugin;

    /** @var array|null [ { CitationModel }, ... ] */
    private ?array $citations = null;

    /** @param CitationManagerPlugin $plugin */
    public function __construct(CitationManagerPlugin $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Execute
     *
     * @param string $submissionId
     * @param string $publicationId
     * @param string $citationsRaw
     * @return bool
     */
    public function execute(string $submissionId,
                            string $publicationId,
                            string $citationsRaw): bool
    {
        if (empty($citationsRaw)) return false;

        // region cleanup and split citations
        $citationsRaw = StringHelper::trim($citationsRaw);
        $citationsRaw = StringHelper::stripSlashes($citationsRaw);
        $citationsRaw = StringHelper::normalizeLineEndings($citationsRaw);
        $citationsRaw = StringHelper::trim($citationsRaw, "\n");

        if (empty($citationsRaw)) return false;

        $citations = explode("\n", $citationsRaw);

        $local = [];
        foreach ($citations as $citationRaw) {
            $citation = new CitationModel();
            $citation->raw = $citationRaw;
            $local[] = $citation;
        }
        $citations = $local;
        // endregion

        if (empty($citations)) return false;

        // region pid extractor citations
        $local = [];
        foreach ($citations as $index => $citation) {
            $rowRaw = $citation->raw;
            $rowRaw = StringHelper::trim($rowRaw, ' .,');
            $rowRaw = StringHelper::stripSlashes($rowRaw);
            $rowRaw = StringHelper::normalizeWhiteSpace($rowRaw);
            $rowRaw = StringHelper::removeNumberPrefixFromString($rowRaw);

            // extract doi
            $citation->doi = Doi::extractFromString($rowRaw);

            // remove doi from raw
            $rowRaw = str_replace(
                Doi::addPrefix($citation->doi),
                '',
                Doi::normalize($rowRaw));

            // parse url (after parsing doi)
            $citation->url = Url::extractFromString($rowRaw);

            // handle
            $citation->url = Handle::normalize($citation->url);

            // arxiv
            $citation->url = Arxiv::normalize($citation->url);

            // urn
            $citation->urn = Urn::extractFromString($rowRaw);

            $local[] = $citation;
        }
        $citations = $local;
        // endregion

        // region enrich citations
        $local = [];
        foreach ($citations as $index => $citation) {
            // skip iteration if isProcessed or doi empty
            if ($citation->isProcessed || empty($citation->doi)) {
                $local[] = $citation;
                continue;
            }

            // OpenAlex Work
            $objOpenAlex = new OpenAlexEnrich($this->plugin);
            $citation = $objOpenAlex->execute($citation);

            // Wikidata
            $objWikidata = new WikidataEnrich($this->plugin);
            $citation = $objWikidata->execute($citation);

            // Orcid
            $objOrcid = new OrcidEnrich($this->plugin);
            $citation = $objOrcid->execute($citation);

            $local[] = $citation;
        }
        $citations = $local;
        // endregion

        $this->citations = $citations;

        // save
        $pluginDao = new PluginDAO();
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

            $submissions = $pluginDao->getBatchProcessSubmissions($contextId);

            /* @var Submission $submission */
            foreach ($submissions as $submission) {

                $publications = $submission->getData('publications');

                /* @var Publication $publication */
                foreach ($publications as $publication) {

                    // skip if declined or citations found
                    if (!empty($pluginDao->getCitations($publication->getId()))
                        || $publication->getData('status') === STATUS_DECLINED) {
                        continue;
                    }

                    // not processed yet, proceed
                    $citationsRaw = $publication->getData('citationRaw');
                    $this->execute($submission->getId(), $publication->getId(), $citationsRaw);
                }
            }
        }

        return true;
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
}
