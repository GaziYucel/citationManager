<?php
/**
 * @file classes/External/Wikidata/Enrich.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Wikidata
 * @brief Wikidata class for Wikidata
 */

namespace APP\plugins\generic\citationManager\classes\External\Wikidata;

use APP\plugins\generic\citationManager\CitationManagerPlugin;
use APP\plugins\generic\citationManager\classes\DataModels\Citation\CitationModel;
use APP\plugins\generic\citationManager\classes\DataModels\Metadata\MetadataJournal;
use APP\plugins\generic\citationManager\classes\DataModels\Metadata\MetadataPublication;
use APP\plugins\generic\citationManager\classes\External\EnrichAbstract;
use APP\plugins\generic\citationManager\classes\External\Wikidata\DataModels\Property;
use APP\plugins\generic\citationManager\classes\PID\Orcid;
use APP\plugins\generic\citationManager\classes\PID\Wikidata;
use APP\author\Author;
use APP\issue\Issue;
use APP\publication\Publication;
use APP\submission\Submission;
use PKP\context\Context;

class Enrich extends EnrichAbstract
{
    /** @var Property */
    public Property $property;

    /** @copydoc EnrichAbstract::__construct */
    public function __construct(CitationManagerPlugin $plugin,
                                ?Context              $context,
                                ?Issue                $issue,
                                ?Submission           $submission,
                                ?Publication          $publication,
                                ?MetadataJournal      $metadataJournal,
                                ?MetadataPublication  $metadataPublication,
                                ?array                $authors,
                                ?array                $citations)
    {
        parent::__construct($plugin, $context, $issue, $submission, $publication,
            $metadataJournal, $metadataPublication, $authors, $citations);

        $this->api = new Api($plugin);
        $this->property = new Property();
    }

    /**
     * Process this external service
     *
     * @return bool
     */
    public function execute(): bool
    {
        // journal
        $this->metadataJournal = $this->processJournal(
            $this->context->getData('onlineIssn'),
            $this->metadataJournal);

        // authors of publication
        $countAuthors = count($this->authors);
        for ($i = 0; $i < $countAuthors; $i++) {
            $this->authors[$i] = $this->processAuthor($this->authors[$i]);
        }

        // citations
        $countCitations = count($this->citations);
        for ($i = 0; $i < $countCitations; $i++) {
            /* @var CitationModel $citation */
            $citation = $this->citations[$i];

            if (!empty($citation->wikidata_id)) continue;

            $citation = $this->processCitation($citation);

            $citation->wikidata_id = Wikidata::removePrefix($citation->wikidata_id);

            $this->citations[$i] = $citation;
        }

        return true;
    }

    /**
     * Get citation (work) from external service
     *
     * @param CitationModel $citation
     * @return CitationModel
     */
    public function processCitation(CitationModel $citation): CitationModel
    {
        $qid = $this->api
            ->getQidFromItem($this->api
                ->getItemWithPropertyAndPid(
                    $this->property->doi['id'], $citation->doi));

        if (!empty($qid)) $citation->wikidata_id = $qid;

        return $citation;
    }

    /**
     * Get wikidata id for journal
     *
     * @param string|null $issn
     * @param MetadataJournal $metadataJournal
     * @return MetadataJournal
     */
    public function processJournal(?string $issn, MetadataJournal $metadataJournal): MetadataJournal
    {
        $metadataJournal->wikidata_id = $this->api
            ->getQidFromItem($this->api
                ->getItemWithPropertyAndPid(
                    $this->property->issnL['id'], $issn));

        return $metadataJournal;
    }

    /**
     * Get wikidata id for author
     *
     * @param Author $author
     * @return Author
     */
    public function processAuthor(Author $author): Author
    {
        $metadata = $author->getData(CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHOR);

        $orcidId = Orcid::removePrefix($author->getData('orcid'));

        if (empty($metadata->wikidata_id) && !empty($orcidId))
            $metadata->wikidata_id = $this->api
                ->getQidFromItem($this->api
                    ->getItemWithPropertyAndPid(
                        $this->property->orcidId['id'], $orcidId));;

        $metadata->wikidata_id = Wikidata::removePrefix($metadata->wikidata_id);

        $author->setData(CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHOR, $metadata);

        return $author;
    }
}
