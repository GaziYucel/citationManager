<?php
/**
 * @file plugins/generic/citationManager/vendor/tibhannover/optimeta/src/OpenCitations/OpenCitationsBase.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class OpenCitationsBase
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief OpenCitationsBase class
 */

namespace Optimeta\Shared\OpenCitations;

use Optimeta\Shared\GitHub\GitHubBase;

class OpenCitationsBase
{
    /**
     * Log string
     * @var string
     */
    public string $log = '';

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

        $this->log .= '[issueId: ' . $issueId . ']';

        if (empty($issueId)) return 0;

        return $issueId;
    }

    function __destruct()
    {
        // error_log('OpenCitationsBase->__destruct: ' . $this->log);
    }
}
