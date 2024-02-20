<?php
/**
 * @file classes/External/GitHub/Api.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Api
 * @brief Api class
 */

namespace APP\plugins\generic\citationManager\classes\External\GitHub;

use APP\core\Application;
use APP\plugins\generic\citationManager\CitationManagerPlugin;
use APP\plugins\generic\citationManager\classes\External\ApiAbstract;
use APP\plugins\generic\citationManager\classes\External\GitHub\DataModels\Issue;
use GuzzleHttp\Client;

class Api extends ApiAbstract
{
    /** @var string The base URL for GitHub API requests. */
    public string $url = 'https://api.github.com/repos';

    /** @var string The owner of the GitHub repository. */
    public string $owner = '';

    /** @var string The authentication token for GitHub API requests. */
    public string $token = '';

    /** @var string The name of the GitHub repository. */
    public string $repository = '';

    /** @var string The default URL suffix for accessing issues in a repository. */
    public string $urlIssuesSuffix = '{owner}/{repository}/issues';

    /**
     * Api constructor.
     *
     * @param CitationManagerPlugin $plugin
     * @param string|null $url The base URL for API requests (optional).
     */
    public function __construct(CitationManagerPlugin $plugin, ?string $url = '')
    {
        parent::__construct($plugin, $url);

        $this->httpClient = new Client(
            [
                'headers' =>
                    [
                        'User-Agent' => Application::get()->getName() . '/' . CITATION_MANAGER_PLUGIN_NAME,
                        'Accept' => 'application/vnd.github.v3+json',
                        'Authorization' => 'token ' . $this->token
                    ]
            ]
        );
    }

    /**
     * Adds an issue to a given repository and returns the issue ID.
     *
     * @param string $title The title of the issue.
     * @param string $body The body or description of the issue.
     * @return int The ID of the created issue, or 0 if unsuccessful.
     */
    public function addIssue(string $title, string $body): int
    {
        if (empty($this->owner) || empty($this->token)
            || empty($this->repository) || empty($title) || empty($body)) {
            return 0;
        }

        $issue = new Issue();
        $issue->title = $title;
        $issue->body = $body;
        $issue->labels = ['Deposit'];

        $result = $this->apiRequest(
            'POST',
            $this->url . "/$this->owner/$this->repository/issues",
            ['json' => (array)$issue]);

        if (!empty($result['number']) && is_numeric($result['number'])) {
            return (int)$result['number'];
        }

        return 0;
    }

    /**
     * Checks whether deposits possible for this service
     *
     * @return bool
     */
    public function isDepositPossible(): bool
    {
        if (empty($this->owner) || empty($this->repository) || empty($this->token)) {
            return false;
        }

        return true;
    }
}
