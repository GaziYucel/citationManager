<?php
/**
 * @file classes/External/Orcid/Api.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Orcid
 * @brief Orcid class for Orcid
 */

namespace APP\plugins\generic\citationManager\classes\External\Orcid;

use APP\core\Application;
use APP\plugins\generic\citationManager\CitationManagerPlugin;
use APP\plugins\generic\citationManager\classes\External\ApiAbstract;
use GuzzleHttp\Client;

class Api extends ApiAbstract
{
    /** @var string The base URL for API requests. */
    public string $url = 'https://pub.orcid.org/v2.1';

    /**
     * @param CitationManagerPlugin $plugin
     * @param string|null $url The base URL for API requests (optional).
     */
    function __construct(CitationManagerPlugin $plugin, ?string $url = '')
    {
        parent::__construct($plugin, $url);

        $this->httpClient = new Client(
            [
                'headers' => [
                    'User-Agent' => Application::get()->getName() . '/' . CITATION_MANAGER_PLUGIN_NAME,
                    'Accept' => 'application/json'
                ],
                'verify' => false
            ]
        );
    }

    /**
     * Gets json object from API and returns the body of the response as array
     *
     * @param string $orcidId
     * @return array
     */
    public function getPerson(string $orcidId): array
    {
        if (empty($orcidId)) return [];

        return $this->apiRequest('GET', '/' . $orcidId, []);
    }
}
