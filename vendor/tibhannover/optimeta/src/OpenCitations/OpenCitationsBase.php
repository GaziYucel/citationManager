<?php

namespace Optimeta\Shared\OpenCitations;

use Optimeta\Shared\GitHub\GitHubBase;

class OpenCitationsBase
{
    /**
     * GitHubBase object
     * @var GitHubBase
     */
    protected GitHubBase $github;

    public function __construct(string $owner, string $repository, string $token)
    {
        $this->github = new GitHubBase($owner, $repository, $token);
    }

    /**
     * @param string $title
     * @param string $body
     * @return int
     */
    public function depositCitations(string $title, string $body): int
    {
        $issueId = 0;

        if (empty($title) || empty($body)) return $issueId;

        $issueId = $this->github->addIssue($title, $body);

        if (empty($issueId)) return 0;

        return $issueId;
    }
}
