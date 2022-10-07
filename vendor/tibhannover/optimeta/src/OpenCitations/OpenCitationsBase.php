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
     * @return int
     */
    public function depositCitations(string $title, string $body): int
    {
        $issueId = 0;

        if(empty($title) || empty($body)) return $issueId;

        $github = new GitHubBase();
        $github->setUrl($this->url);
        $github->setToken($this->token);

        $issueId = $github->addIssue($title, $body);

        if(empty($issueId)) return 0;

        return $issueId;
    }
}
