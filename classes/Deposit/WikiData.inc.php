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

use Optimeta\Shared\Pid\Doi;
use Optimeta\Shared\Pid\Orcid;
use Optimeta\Shared\OpenCitations\Model\WorkMetaData;
use Optimeta\Shared\WikiData\WikiDataBase;
use OptimetaCitationsPlugin;

class WikiData
{
    /**
     * Log string
     * @var string
     */
    public string $log = '';

    /**
     * Submits work to WikiData
     * @param string $submissionId
     * @param array $citations
     * @return string
     */
    public function submitWork(string $submissionId, array $citations): string
    {
        $work = [];

        $plugin = new OptimetaCitationsPlugin();

        $request = $plugin->getRequest();
        $context = $request->getContext(); // journal

        $submissionDao = \DAORegistry::getDAO('SubmissionDAO');
        $submission = $submissionDao->getById($submissionId);

        $publication = $submission->getLatestPublication();
        $authors = $submission->getAuthors();

        $issueDao = \DAORegistry::getDAO('IssueDAO');
        $issueId = $publication->getData('issueId');

        $doi = '';
        if (!empty($submission->getStoredPubId('doi'))) $doi = $submission->getStoredPubId('doi');

        $issue = null;
        $publicationDate = '';
        if (!is_null($issueDao->getById($issueId))) {
            $issue = $issueDao->getById($issueId);
            $publicationDate = date('\+Y-m-d\T00:00:00\Z', strtotime($issue->getData('datePublished')));
        }

        if (empty($doi) || empty($issue)) return '';

        $wikiDataBase = new WikiDataBase(
            OPTIMETA_CITATIONS_IS_TEST_ENVIRONMENT,
            $plugin->getSetting($context->getId(), OPTIMETA_CITATIONS_WIKIDATA_USERNAME),
            $plugin->getSetting($context->getId(), OPTIMETA_CITATIONS_WIKIDATA_PASSWORD));

        // add main article
        $locale = $publication->getData('locale');
        $work["locale"] = $locale;
        $work["label"] = $title = $publication->getData('title', $locale);
        // debug data
        $work["label"] .= ' [' . date('Y-m-d H:i:s') . ']';

        $work["claims"]["doi"] = $doi;
        $work["claims"]["publicationDate"] = $publicationDate;

        // check if article/item exists
        $qid = $wikiDataBase->getEntity($doi, '');
        if (empty($qid)) $work["qid"] = $qid;

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
