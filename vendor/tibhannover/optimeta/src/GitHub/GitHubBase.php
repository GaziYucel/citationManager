<?php

namespace Optimeta\Shared\GitHub;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GitHubBase
{
    /**
     * User agent name to identify us
     * @var string
     */
    protected string $userAgent = 'OJSOptimetaCitations';

    /**
     * @desc The base url to the api issues
     * @var string
     */
    protected $url = 'https://api.github.com/repos/{{owner}}/{{repository}}/issues';

    /**
     * @desc Access token
     * @var string
     */
    protected $token;

    /**
     * @desc GuzzleHttp\Client
     * @var object (class)
     */
    protected object $client;

    public function __construct(string $owner, string $repository, string $token)
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
     * @desc Adds an issue to a given repository
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
            error_log($ex->getMessage(), true);
        }

        return $issueId;
    }

    /**
     * @desc Get issue id from response
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