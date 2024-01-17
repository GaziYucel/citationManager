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

use APP\plugins\generic\optimetaCitations\OptimetaCitationsPlugin;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Api extends \APP\plugins\generic\optimetaCitations\classes\Api
{
    /**
     * User agent name to identify us
     * @var string
     */
    protected string $userAgent = 'OJSOptimetaCitations';

    /**
     * Log string
     * @var string
     */
    public string $log = '';

    /**
     * The base url to the api issues
     * @var string
     */
    protected $url = 'https://api.github.com/repos/{{owner}}/{{repository}}/issues';

    /**
     * Access token
     * @var string
     */
    protected $token;

    /**
     * GuzzleHttp\Client
     * @var object (class)
     */
    protected object $client;

    function __construct(
        OptimetaCitationsPlugin $plugin, string $url,
        ?string                 $username = '', ?string $password = '', ?array $httpClientOptions = [])
    {
        parent::__construct($plugin, $url, $username, $password, $httpClientOptions);
    }

    public function __construct22(string $owner, string $repository, string $token)
    {
        if (!empty(OPTIMETA_CITATIONS_USER_AGENT))
            $this->userAgent = OPTIMETA_CITATIONS_USER_AGENT;

        $this->url = strtr($this->url, [
            '{{owner}}' => $owner,
            '{{repository}}' => $repository
        ]);

        $this->token = $token;

        $this->client = new Client([
            'headers' => ['User-Agent' => $this->userAgent],
            'verify' => false
        ]);
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
            $response = $this->client->request(
                'POST',
                $this->url,
                [
                    'headers' => [
                        'User-Agent' => $this->userAgent,
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
            $this->log .= $ex->getMessage();
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
            $this->log .= $ex->getMessage();
        }

        return $issueId;
    }

    function __destruct()
    {
        // error_log('GitHubBase->__destruct: ' . $this->log);
    }
}