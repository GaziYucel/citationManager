<?php
/**
 * @file classes/External/OpenCitations/Api.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Api
 * @brief Api class
 */

namespace APP\plugins\generic\citationManager\classes\External\OpenCitations;

use APP\plugins\generic\citationManager\CitationManagerPlugin;
use APP\plugins\generic\citationManager\classes\External\GitHub\Api as GitHubApi;

class Api extends GitHubApi
{
    /**
     * @param CitationManagerPlugin $plugin
     * @param string|null $url The base URL for API requests (optional).
     */
    public function __construct(CitationManagerPlugin $plugin, ?string $url = '')
    {
        parent::__construct($plugin, $url);

        $this->owner = $this->plugin->getSetting($this->plugin->getCurrentContextId(),
            CitationManagerPlugin::CITATION_MANAGER_OPEN_CITATIONS_OWNER);

        $this->token = $this->plugin->getSetting($this->plugin->getCurrentContextId(),
            CitationManagerPlugin::CITATION_MANAGER_OPEN_CITATIONS_TOKEN);

        $this->repository = $this->plugin->getSetting($this->plugin->getCurrentContextId(),
            CitationManagerPlugin::CITATION_MANAGER_OPEN_CITATIONS_REPOSITORY);
    }
}
