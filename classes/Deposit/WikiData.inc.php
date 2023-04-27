<?php
/**
 * @file plugins/generic/optimetaCitations/classes/Deposit/WikiData.inc.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class WikiData
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Depositor class WikiData
 */

namespace Optimeta\Citations\Deposit;

use Journal;
use Optimeta\Shared\Pid\Doi;
use Optimeta\Shared\Pid\Orcid;
use Optimeta\Shared\OpenCitations\Model\WorkMetaData;
use Optimeta\Shared\WikiData\WikiDataBase;
use OptimetaCitationsPlugin;
use Publication;

class WikiData
{
    /**
     * Is this instance production
     * @var bool
     */
    protected bool $isProduction = false;

    /**
     * Log string
     * @var string
     */
    public string $log = '';

    /**
     * Instance of OptimetaCitationsPlugin
     * @var object OptimetaCitationsPlugin
     */
    protected object $plugin;

    public function __construct()
    {
        $this->plugin = new OptimetaCitationsPlugin();
        if ($this->plugin->getSetting($this->plugin->getCurrentContextId(),
                OPTIMETA_CITATIONS_IS_PRODUCTION_KEY) === 'true') {
            $this->isProduction = true;
        }
    }

    /**
     * Submits work to WikiData
     * @param Journal $context
     * @param object|null $issue
     * @param object $submission
     * @param Publication $publication
     * @param array $authors
     * @param array $publicationWork
     * @param array $citations
     * @return string
     */
    public function submitWork(
        Journal     $context,
        ?object     $issue,
        object      $submission,
        Publication $publication,
        array       $authors,
        array       $publicationWork,
        array       $citations): string
    {
        $username = $this->plugin->getSetting($context->getId(),
            OPTIMETA_CITATIONS_WIKIDATA_USERNAME);
        $password = $this->plugin->getSetting($context->getId(),
            OPTIMETA_CITATIONS_WIKIDATA_PASSWORD);

        // return '' url not empty or username and password empty
        if (empty($username) || empty($password))
            return '';

        $work = [
            'qid' => '',
            'locale' => '',
            'label' => '',
            'claims' => [
                'doi' => '',
                'publicationDate' => ''
            ]
        ];

        $doi = $submission->getStoredPubId('doi');

        $publicationDate = date('Y-m-d', strtotime($issue->getData('datePublished')));

        $wikiDataBase = new WikiDataBase(!$this->isProduction, $username, $password);

        // add main article
        $locale = $publication->getData('locale');
        $work["locale"] = $locale;
        $work["label"] = $title = $publication->getData('title', $locale); // . ' [' . date('Y-m-d H:i:s') . ']';

        $work["claims"]["doi"] = $doi;
        $work["claims"]["publicationDate"] = $publicationDate;

        // check if article/item exists
        $work["qid"] = $wikiDataBase->getEntity($doi, '');

        $qidNew = $wikiDataBase->submitWork($work);

        $this->log .= $qidNew;

        return $qidNew;
    }

    /**
     * Return work as an array
     * @param $submission
     * @param $publication
     * @param $authors
     * @param $issue
     * @param $journal
     * @return array
     */
    public function getWorkAsArray($submission, $publication, $authors, $issue, $journal): array
    {
        $work = new WorkMetaData();
        $objOrcid = new Orcid();

        $locale = $publication->getData('locale');

        $objDoi = new Doi();
        $work->id = 'doi:' . $objDoi->removePrefixFromUrl($submission->getStoredPubId('doi'));

        $work->title = $publication->getData('title')[$locale];

        foreach ($authors as $index => $data) {
            $work->author .= $data->getData('familyName')[$locale] . ', ' . $data->getData('givenName')[$locale];
            if (!empty($data->getData('orcid'))) {
                $work->author .= ' [orcid:' . $objOrcid->removePrefixFromUrl($data->getData('orcid')) . ']';
            }
            $work->author .= '; ';
        }
        $work->author = trim($work->author, '; ');

        $work->pub_date = '';
        if (!empty($issue->getData('datePublished'))) $work->pub_date = date('Y-m-d', strtotime($issue->getData('datePublished')));

        $work->venue = $journal->getData('name')[$locale];
        $venueIds = '';
        if (!empty($journal->getData('onlineIssn'))) $venueIds .= 'issn:' . $journal->getData('onlineIssn') . ' ';
        if (!empty($journal->getData('printIssn'))) $venueIds .= 'issn:' . $journal->getData('printIssn') . ' ';
        if (!empty($issue->getStoredPubId('doi'))) $venueIds .= 'doi:' . $issue->getStoredPubId('doi') . ' ';
        if (!empty($venueIds)) $work->venue = trim($work->venue) . ' ' . '[' . trim($venueIds) . ']';

        $work->volume = '';
        if (!empty($issue->getData('volume'))) $work->volume = $issue->getData('volume');

        $work->issue = '';
        if (!empty($issue->getData('number'))) $work->issue = $issue->getData('number');

        $work->page = '';
        $work->type = 'scholarly article';
        if (!empty($journal->getData('publisherInstitution'))) $work->publisher = $journal->getData('publisherInstitution');
        $work->editor = '';

        $values = '';
        foreach ($work as $name => $value) {
            $values .= '"' . str_replace('"', '\"', $value) . '",';
        }
        $values = trim($values, ',');
        $values = $values . PHP_EOL;

//        return $values;

        return [];
    }

    function __destruct()
    {
        error_log('WikiData->__destruct: ' . $this->log);
    }
}
