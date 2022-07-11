<?php
namespace Optimeta\Shared\GitHub;

use Optimeta\Shared\OptimetaBase;

class GitHubBase extends OptimetaBase
{
    /**
     * @desc The url to the api
     * @var string
     */
    protected $baseUrlIssues = 'https://api.github.com/repos/%s/%s/issues';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @desc Adds an issue to a given repository
     * @param string $title
     * @param string $body
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function addIssue(string $title, string $body): bool
    {
        if(empty($this->url) || empty($this->token) || empty($title) || empty($body)) {
            return false;
        }

        try{
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
        }
        catch(\Exception $ex){
            $this->errors = $ex;
        }

        return true;
    }
}