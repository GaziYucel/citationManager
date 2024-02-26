<?php
/**
 * @file classes/External/Wikidata/Deposit.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Wikidata
 * @brief Depositor class Wikidata
 */

namespace APP\plugins\generic\citationManager\classes\External\Wikidata;

use APP\plugins\generic\citationManager\CitationManagerPlugin;
use APP\plugins\generic\citationManager\classes\DataModels\Citation\CitationModel;
use APP\plugins\generic\citationManager\classes\DataModels\Metadata\MetadataAuthor;
use APP\plugins\generic\citationManager\classes\DataModels\Metadata\MetadataPublication;
use APP\plugins\generic\citationManager\classes\Db\PluginDAO;
use APP\plugins\generic\citationManager\classes\External\DepositAbstract;
use APP\plugins\generic\citationManager\classes\External\Wikidata\DataModels\Claim;
use APP\plugins\generic\citationManager\classes\External\Wikidata\DataModels\Property;
use APP\plugins\generic\citationManager\classes\Helpers\ClassHelper;
use APP\plugins\generic\citationManager\classes\Helpers\LogHelper;
use APP\plugins\generic\citationManager\classes\PID\Orcid;
use Author;
use Context;
use Issue;
use Journal;
use Publication;
use Submission;

class Deposit extends DepositAbstract
{
    /** @var MetadataPublication|null */
    private ?MetadataPublication $publicationMetadata = null;

    /** @var array|null */
    private ?array $citations = [];

    /** @var array|null */
    private ?array $authors = [];

    /** @var Property */
    private Property $property;

    /** @param CitationManagerPlugin $plugin */
    public function __construct(CitationManagerPlugin $plugin)
    {
        $this->plugin = $plugin;
        $this->api = new Api($plugin);
        $this->property = new Property();
    }

    /**
     * Executes deposits to external services
     *
     * @param Journal $context
     * @param Issue $issue
     * @param Submission $submission
     * @param Publication $publication
     * @param MetadataPublication $publicationMetadata
     * @param array $citations
     * @return bool
     */
    public function execute(Context             $context,
                            Issue               $issue,
                            Submission          $submission,
                            Publication         $publication,
                            MetadataPublication $publicationMetadata,
                            array               $citations): bool
    {
        $this->publicationMetadata = $publicationMetadata;
        $this->citations = $citations;

        // return false if required data not provided
        if (!$this->api->isDepositPossible()) return false;

        $locale = $publication->getData('locale');

        $pluginDao = new PluginDAO();

        // journal
        $journalMetaData = $pluginDao->getMetadataJournal($publication->getId());
        if (empty($journalMetaData->wikidata_id)) {
            $journalMetaData->wikidata_id = $this->processJournal($locale, $context);
            $pluginDao->saveMetadataJournal($publication->getId(), $journalMetaData);
        }

        // author(s)
        $authors = [];
        foreach ($publication->getData('authors') as $id => $authorLC) {
            /* @var Author $authorLC */
            $author = (array)$authorLC;
            $metadata = json_decode($author['_data'][CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHOR], true);
            $metadata = ClassHelper::getClassWithValuesAssigned(new MetadataAuthor(), $metadata);
            if (empty($metadata)) $metadata = new MetadataAuthor();
            $author['_data'][CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHOR] = $metadata;

            $orcidId = Orcid::removePrefix($authorLC->getData('orcid'));
            $displayName = trim($authorLC->getGivenName($locale) . ' ' . $authorLC->getFamilyName($locale));

            if (empty($metadata->wikidata_id) && !empty($orcidId) && !empty($displayName)) {
                $metadata->wikidata_id = $this->processAuthor($locale, $orcidId, $displayName);
            }
            $authors[] = $author;

            $pluginDao->saveMetadataAuthor($authorLC->getId(), $metadata);
        }

        // cited articles
        $countCitations = count($citations);
        for ($i = 0; $i < $countCitations; $i++) {
            /* @var CitationModel $citation */
            $citation = Classhelper::getClassWithValuesAssigned(new CitationModel(), $citations[$i]);

            if (empty($citation->wikidata_id))
                $citation->wikidata_id = $this->processCitedArticle($locale, $citation);

            $citations[$i] = $citation;
        }

        // main article
        $publicationMetadata->wikidata_id = $this->processMainArticle($locale, $issue, $publication);

        $this->publicationMetadata = $publicationMetadata;
        $this->citations = $citations;
        $this->authors = $authors;

        if (empty($publicationMetadata->wikidata_id)) return false;

        // get main article
        $item = $this->api->getItemWithQid($publicationMetadata->wikidata_id);

        // published in main article
        $this->addReferenceClaim($item,
            (array)$journalMetaData,
            $this->property->publishedIn['id']);

        // authors in main article
        foreach ($authors as $index => $entity) {
            $this->addReferenceClaim($item,
                (array)$entity['_data'][CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHOR],
                $this->property->author['id']);
        }

        // cites work in main article
        foreach ($citations as $index => $entity) {
            $this->addReferenceClaim($item,
                (array)$entity,
                $this->property->citesWork['id']);
        }

        return true;
    }

    /**
     * Return publication metadata
     *
     * @return MetadataPublication
     */
    public function getPublicationMetadata(): MetadataPublication
    {
        if (empty($this->publicationMetadata))
            return new MetadataPublication();

        return $this->publicationMetadata;
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

    /**
     * Create journal and return QID
     *
     * @param string $locale
     * @param Context $context
     * @return string
     */
    private function processJournal(string $locale, Context $context): string
    {
        $pid = $context->getData('onlineIssn');
        $label = $context->getData('name')[$locale];

        if (empty($pid) || empty($label)) return '';

        // find qid and return qid if found
        $qid = $this->api
            ->getQidFromItem($this->api
                ->getItemWithPropertyAndPid(
                    $this->property->doi['id'], $pid));
        if (!empty($qid)) return $qid;

        // not found, create and return qid
        $claim = new Claim();

        $data['labels'] = $claim->getLabels($locale, $label);

        $data['claims'] = [
            $claim->getInstanceOf(
                $this->property->instanceOfScientificJournal['id'],
                $this->property->instanceOfScientificJournal['default']),
            $claim->getExternalId(
                $this->property->issnL['id'],
                $pid),
            $claim->getMonoLingualText(
                $this->property->title['id'],
                $label,
                $locale)
        ];

        return $this->api->addItemAndReturnQid($data);
    }

    /**
     * Create author and return QID
     *
     * @param string $locale
     * @param string $pid
     * @param string $label
     * @return string
     */
    private function processAuthor(string $locale, string $pid, string $label): string
    {
        // find qid and return qid if found
        $qid = $this->api
            ->getQidFromItem($this->api
                ->getItemWithPropertyAndPid(
                    $this->property->orcidId['id'], $pid));
        if (!empty($qid)) return $qid;

        // not found, create and return qid
        $claim = new Claim();

        $data['labels'] = $claim->getLabels($locale, $label);

        $data['claims'] = [
            $claim->getInstanceOf(
                $this->property->instanceOfHuman['id'],
                $this->property->instanceOfHuman['default']
            ),
            $claim->getExternalId(
                $this->property->orcidId['id'],
                $pid)
        ];

        return $this->api->addItemAndReturnQid($data);
    }

    /**
     * Create cited article and return QID
     *
     * @param string $locale
     * @param CitationModel $citation
     * @return string
     */
    private function processCitedArticle(string $locale, CitationModel $citation): string
    {
        if (empty($locale) || empty($citation->doi)) return '';

        $pid = $citation->doi;
        $label = $citation->title;

        // find qid and return qid if found
        $qid = $this->api
            ->getQidFromItem($this->api
                ->getItemWithPropertyAndPid(
                    $this->property->doi['id'], $pid));
        if (!empty($qid)) return $qid;

        // not found, create and return qid
        $claim = new Claim();

        $data['labels'] = $claim->getLabels($locale, $label);

        $data['claims'] = [
            $claim->getInstanceOf(
                $this->property->instanceOfScientificArticle['id'],
                $this->property->instanceOfScientificArticle['default']),
            $claim->getExternalId(
                $this->property->doi['id'],
                $pid),
            $claim->getMonoLingualText(
                $this->property->title['id'],
                $label,
                $locale)
        ];

        return $this->api->addItemAndReturnQid($data);
    }

    /**
     * Create main article and return QID
     *
     * @param string $locale
     * @param Issue $issue
     * @param Publication $publication
     * @return string
     */
    private function processMainArticle(string $locale, Issue $issue, Publication $publication): string
    {
        $pluginDao = new PluginDAO();

        // find qid and return qid if found
        $qid = $this->api
            ->getQidFromItem($this->api
                ->getItemWithPropertyAndPid(
                    $this->property->doi['id'], $publication->getStoredPubId('doi')
                )
            );
        if (!empty($qid)) return $qid;

        // not found, create and return qid
        $claim = new Claim();
        $data['labels'] = $claim->getLabels($locale, $publication->getData('title')[$locale]);

        $data['claims'] = [
            $claim->getInstanceOf(
                $this->property->instanceOfScientificArticle['id'],
                $this->property->instanceOfScientificArticle['default']),
            $claim->getExternalId(
                $this->property->doi['id'],
                $publication->getStoredPubId('doi')),
            $claim->getMonoLingualText(
                $this->property->title['id'],
                $publication->getData('title')[$locale],
                $locale),
            $claim->getPointInTime(
                $this->property->publicationDate['id'],
                date('+Y-m-d\T00:00:00\Z', strtotime($issue->getData('datePublished')))),
            $claim->getString(
                $this->property->volume['id'],
                $issue->getVolume())
        ];

        return $this->api->addItemAndReturnQid($data);
    }

    /**
     * Add published in reference to the main article.
     *
     * @param array $item https://www.wikidata.org/w/api.php?action=wbgetentities&ids=Q106622495
     * @param array $referenced MetadataAuthor, MetadataJournal
     * @param string $property
     * @return bool
     */
    private function addReferenceClaim(array $item, array $referenced, string $property): bool
    {
        if (empty($referenced['wikidata_id'])) return false;

        $createClaim = true;

        if (!empty($item['claims'][$property])) {
            foreach ($item['claims'][$property] as $index => $claim) {
                if (strtolower($claim['mainsnak']['datavalue']['value']['id'])
                    === strtolower($referenced['wikidata_id'])) {
                    $createClaim = false;
                }
            }
        }

        $claim = new Claim();

        if ($createClaim) {
            return $this->api->createWikibaseItemClaim(
                $item['title'],
                $property,
                $claim->getWikibaseItemReference($referenced['wikidata_id']));
        }

        return false;
    }
}
