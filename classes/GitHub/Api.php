<?php
/**
 * @file plugins/generic/optimetaCitations/classes/GitHub/Api.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Api
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Api class
 */

namespace APP\plugins\generic\optimetaCitations\classes\GitHub;

use APP\core\Application;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Api
{
    /**
     * @var string
     */
    public string $userAgent = OPTIMETA_CITATIONS_PLUGIN_NAME;

    /**
     * @var string
     */
    public string $url = 'https://api.github.com/repos';

    /**
     * @var Client
     */
    public Client $httpClient;

    /**
     * @var string
     */
    public string $owner;

    /**
     * @var string
     */
    public string $token;

    /**
     * @var string
     */
    public string $urlIssuesSuffix = '{owner}/{repository}/issues';

    /**
     * @var string
     */
    public string $repository;

    public function __construct(string $owner, string $token, string $repository, ?string $url = '')
    {
        $this->userAgent = Application::get()->getName() . '/' . $this->userAgent;

        $this->owner = $owner;
        $this->token = $token;
        $this->repository = $repository;
        if (!empty($url)) $this->url = $url;

        $this->httpClient = new Client();
    }

    /**
     * Adds an issue to a given repository
     * @param string $title
     * @param string $body
     * @return int
     */
    public function addIssue(string $title, string $body): int
    {
        $issueId = 0;

        if (empty($this->owner) || empty($this->token)
            || empty($this->repository) || empty($title) || empty($body)) {
            return $issueId;
        }

        $urlIssues = strtr(
            $this->url . '/' . $this->urlIssuesSuffix,
            [
                '{owner}' => $this->owner,
                '{repository}' => $this->repository
            ]
        );

        try {
            $response = $this->httpClient->request(
                'POST',
                $urlIssues,
                [
                    'headers' => [
                        'User-Agent' => Application::get()->getName() . '/' . $this->plugin->getDisplayName(),
                        'Accept' => 'application/vnd.github.v3+json',
                        'Authorization' => 'token ' . $this->token
                    ],
                    'json' => [
                        'title' => $title,
                        'body' => $body,
                        'labels' => ['Deposit']
                    ]
                ]);

            $issueId = $this->getIssueId($response);
        } catch (GuzzleException|\Exception $ex) {
            error_log($ex->getMessage());
        }

        return $issueId;
    }

    /**
     * Get issue id from response
     *
     * @param $response
     * @return int
     */
    public function getIssueId($response): int
    {
        $issueId = 0;

        if (empty($response)) return $issueId;

        try {
            foreach ((array)$response as $key => $value) {

                if (stristr($key, 'stream')) {
                    $objValue = json_decode($value, true);

                    if (is_numeric($objValue['number'])) {
                        $issueId = (int)$objValue['number'];
                    }
                }
            }
        } catch (\Exception $ex) {
            error_log($ex->getMessage());
        }

        return $issueId;
    }
}