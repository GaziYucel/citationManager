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
use APP\plugins\generic\optimetaCitations\OptimetaCitationsPlugin;

class Api
{
    /**
     * @var OptimetaCitationsPlugin
     */
    public OptimetaCitationsPlugin $plugin;

    /**
     * @var string
     */
    protected string $url = 'https://api.github.com/repos/{{owner}}/{{repository}}/issues';

    /**
     * @var Client
     */
    protected Client $httpClient;


    /**
     * @var string
     */
    protected string $token;

    public function __construct(
        OptimetaCitationsPlugin $plugin, string $url, string $owner, string $repository, string $token)
    {
        $this->plugin = $plugin;

        $this->url = strtr($this->url, [
            '{{owner}}' => $owner,
            '{{repository}}' => $repository
        ]);

        $this->token = $token;

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

        if (empty($this->url) || empty($this->token) || empty($title) || empty($body)) {
            return $issueId;
        }

        try {
            $response = $this->httpClient->request(
                'POST',
                $this->url,
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
            error_log($ex->getMessage(), true);
        }

        return $issueId;
    }

    /**
     * Get issue id from response
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
            error_log($ex->getMessage(), true);
        }

        return $issueId;
    }
}