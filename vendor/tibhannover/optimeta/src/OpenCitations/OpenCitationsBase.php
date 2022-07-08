<?php
namespace Optimeta\Shared\OpenCitations;

use Optimeta\Shared\GitHub\GitHubBase;
use Optimeta\Shared\OptimetaBase;

class OpenCitationsBase extends OptimetaBase
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param string $title
     * @param string $body
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function depositCitations(string $title, string $body): bool
    {
        $github = new GitHubBase();
        $github->setUrl($this->url);
        $github->setToken($this->token);

        return $github->addIssue($title, $body);
    }
}
