<?php
/**
 * @file plugins/generic/optimetaCitations/classes/Deposit/OpenCitations.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class OpenCitations
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Depositor class for OpenCitations
 */

namespace APP\plugins\generic\optimetaCitations\classes\Deposit;

use APP\journal\Journal;
use APP\plugins\generic\optimetaCitations\OptimetaCitationsPlugin;
use APP\publication\Publication;
use APP\plugins\generic\optimetaCitations\classes\PID\Arxiv;
use APP\plugins\generic\optimetaCitations\classes\PID\Doi;
use APP\plugins\generic\optimetaCitations\classes\PID\Handle;
use APP\plugins\generic\optimetaCitations\classes\PID\Orcid;
use Optimeta\Shared\OpenCitations\Model\WorkCitation;
use Optimeta\Shared\OpenCitations\Model\WorkMetaData;
use Optimeta\Shared\OpenCitations\OpenCitationsBase;

class OpenCitations
{
    /**
     * Log string
     *
     * @var string
     */
    public string $log = '';

    /**
     * @var OptimetaCitationsPlugin
     */
    protected OptimetaCitationsPlugin $plugin;

    /**
     * The base url to the public issues
     *
     * @var string
     */
    protected string $urlIssues = 'https://github.com/{{owner}}/{{repository}}/issues';

    /**
     * The base url to the api issues
     *
     * @var string
     */
    protected string $urlIssuesApi = 'https://api.github.com/repos/{{owner}}/{{repository}}/issues';

    /**
     * The syntax for the title of the issue
     *
     * @var string
     */
    protected string $titleSyntax = 'deposit {{domain}} {{pid}}';

    /**
     * The separator to separate the work and the citations CSV
     *
     * @var string
     */
    protected string $separator = '===###===@@@===';

    /**
     * Default article type
     *
     * @var string
     */
    protected string $defaultType = 'journal article';

    public function __construct(OptimetaCitationsPlugin $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Submits work to OpenCitations
     *
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
        Journal $context,
        ?object $issue,
        object $submission,
        Publication $publication,
        array $authors,
        array $publicationWork,
        array $citations): string
    {
        $owner = $this->plugin->getSetting($this->plugin->getCurrentContextId(),
            $this->plugin::OPTIMETA_CITATIONS_OPEN_CITATIONS_OWNER);
        $repo = $this->plugin->getSetting($this->plugin->getCurrentContextId(),
            $this->plugin::OPTIMETA_CITATIONS_OPEN_CITATIONS_REPOSITORY);
        $token = $this->plugin->getSetting($this->plugin->getCurrentContextId(),
            $this->plugin::OPTIMETA_CITATIONS_OPEN_CITATIONS_TOKEN);

        // return '' url not empty or username and password empty
        if (empty($owner) || empty($repo) || empty($token))
            return '';

        $doi = $submission->getStoredPubId('doi');

        $publicationDate = date('Y-m-d', strtotime($issue->getData('datePublished')));

        $objDoi = new Doi();
        // title of GitHub issue
        $title = str_replace('{{domain}} {{pid}}',
            $_SERVER['SERVER_NAME'] . ' ' . 'doi:' . $objDoi->removePrefixFromUrl($doi),
            $this->titleSyntax);

        // body of GitHub issue
        $body =
            $this->getColumnNamesAsCsv(new WorkMetaData()) .
            $this->getWorkAsCsv($submission, $publication, $authors, $issue, $context) .
            $this->getCitationsAsWorkAsCsv($citations) .
            $this->separator . PHP_EOL .
            $this->getColumnNamesAsCsv(new WorkCitation()) .
            $this->getCitationsAsCsv($citations, $doi, $publicationDate);

        // prepare open citations and deposit
        $openCitations = new OpenCitationsBase( $owner, $repo, $token);

        $githubIssueId = $openCitations->depositCitations($title, $body);

        $githubIssueUrl = str_replace(
            '{{owner}}/{{repository}}',
            $owner . '/' . $repo,
            $this->urlIssues);

        $this->log .= '[opencitations_url: ' . $githubIssueUrl . '/' . $githubIssueId . ']';

        if (!empty($githubIssueId) && $githubIssueId != 0) {
            return $githubIssueUrl . '/' . $githubIssueId;
        }

        return '';
    }

    /**
     * Get Column names in comma separated format
     *
     * @param object $object
     * @return string
     */
    public function getColumnNamesAsCsv(object $object): string
    {
        $names = '';

        foreach ($object as $name => $value) {
            $names .= '"' . str_replace('"', '\"', $name) . '",';
        }

        return trim($names, ',') . PHP_EOL;
    }

    /**
     * Get Work as WorkModel in comma separated format
     *
     * @param $submission
     * @param $publication
     * @param $authors
     * @param $issue
     * @param $journal
     * @return string
     */
    public function getWorkAsCsv($submission, $publication, $authors, $issue, $journal): string
    {
        $work = new WorkMetaData();

        $locale = $publication->getData('locale');

        $objDoi = new Doi();
        $work->id = 'doi:' . $objDoi->removePrefixFromUrl($submission->getStoredPubId('doi'));

        $work->title = $publication->getData('title')[$locale];

        foreach ($authors as $index => $data) {
            $work->author .= $data->getData('familyName')[$locale] . ', ' . $data->getData('givenName')[$locale];
            if (!empty($data->getData('orcid'))) {
                $objOrcid = new Orcid();
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
        $work->type = $this->defaultType;
        if (!empty($journal->getData('publisherInstitution'))) $work->publisher = $journal->getData('publisherInstitution');
        $work->editor = '';

        $values = '';

        foreach ($work as $name => $value) {
            $values .= '"' . str_replace('"', '\"', $value) . '",';
        }

        return trim($values, ',') . PHP_EOL;
    }

    /**
     * Get Citations as WorkModel in comma separated format
     *
     * @param array $citations
     * @return string
     */
    public function getCitationsAsWorkAsCsv(array $citations): string
    {
        $values = '';

        foreach ($citations as $index1 => $row) {

            $work = new WorkMetaData();

            $objDoi = new Doi();
            if (!empty($row['doi'])) $work->id .= 'doi:' . $objDoi->removePrefixFromUrl($row['doi']) . ' ';
            if (!empty($row['url'])) $work->id .= $this->getUrl($row['url']) . ' ';
            if (!empty($row['urn'])) $work->id .= 'urn:' . str_replace(' ', '', $row['urn']) . ' ';
            $work->id = trim($work->id);

            $work->title = $row['title'];

            $work->author = '';
            if (!empty($row['authors'])) {
                foreach ($row['authors'] as $index2 => $author) {
                    if (empty($author['orcid'])) {
                        $work->author .= $author['display_name'];
                    } else {
                        $work->author .= $author['family_name'] . ', ' . $author['given_name'];
                    }
                    $objOrcid = new Orcid();
                    $work->author .= ' [orcid:' . $objOrcid->removePrefixFromUrl($author['orcid']) . ']';
                    $work->author .= '; ';
                }
                $work->author = trim($work->author, '; ');
            }

            $work->pub_date = $row['publication_date'];

            $work->venue = $row['venue_name'];
            if (!empty($row['venue_issn_l'])) $work->venue .= ' [issn:' . $row['venue_issn_l'] . ']';

            $work->volume = $row['volume'];
            $work->issue = $row['issue'];
            $work->page = '';
            $work->type = str_replace('-', ' ', $row['type']);
            $work->publisher = $row['venue_publisher'];
            $work->editor = '';

            if (!empty($work->id)) {
                foreach ($work as $name => $value) {
                    $values .= '"' . str_replace('"', '\"', $value) . '",';
                }
                $values = trim($values, ',');
                $values = $values . PHP_EOL;
            }
        }

        return $values;
    }

    /**
     * Get Citations in comma separated format
     *
     * @param array $citations
     * @param string $doi
     * @param string $publicationDate
     * @return string
     */
    public function getCitationsAsCsv(array $citations, string $doi, string $publicationDate): string
    {
        $values = '';

        foreach ($citations as $index => $row) {
            $citation = new WorkCitation();

            $citation->citing_id = 'doi:' . $doi;
            $citation->citing_publication_date = $publicationDate;

            $objDoi = new Doi();
            $citation->cited_id = '';
            if (!empty($row['doi'])) $citation->cited_id .= 'doi:' . $objDoi->removePrefixFromUrl($row['doi']) . ' ';
            if (!empty($row['url'])) $citation->cited_id .= $this->getUrl($row['url']) . ' ';
            if (!empty($row['urn'])) $citation->cited_id .= 'urn:' . str_replace(' ', '', $row['urn']) . ' ';
            $citation->cited_id = trim($citation->cited_id);

            $citation->cited_publication_date = $row['publication_date'];

            if (!empty($citation->cited_id)) {
                foreach ($citation as $name => $value) {
                    $values .= '"' . str_replace('"', '\"', $value) . '",';
                }
                $values = trim($values, ',');
                $values = $values . PHP_EOL;
            }
        }

        return $values;
    }

    /**
     * Get url as arxiv, handle or url
     * @param string $url
     * @return string
     */
    private function getUrl(string $url): string
    {
        $urlNew = '';

        $objHandle = new Handle();
        $url = str_replace($objHandle->prefixInCorrect, $objHandle->prefix, $url);

        $objArxiv = new Arxiv();
        $url = str_replace($objArxiv->prefixInCorrect, $objArxiv->prefix, $url);

        if (str_contains($url, $objArxiv->prefix)) {
            $urlNew .= 'arxiv:' . $objArxiv->removePrefixFromUrl($url) . ' ';
        } else if (str_contains($url, $objHandle->prefix)) {
            $urlNew .= 'handle:' . $objHandle->removePrefixFromUrl($url) . ' ';
        } else {
            $urlNew .= 'url:' . str_replace(' ', '', $url) . ' ';
        }

        return trim($urlNew);
    }

    function __destruct()
    {
        // error_log('OpenCitations->__destruct: ' . $this->log);
    }
}