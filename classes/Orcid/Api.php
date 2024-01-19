<?php
/**
 * @file plugins/generic/optimetaCitations/classes/Orcid/Api.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Orcid
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Orcid class for Orcid
 */

namespace APP\plugins\generic\optimetaCitations\classes\Orcid;

use APP\core\Application;
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
    public string $url = 'https://pub.orcid.org/v2.1';

    /**
     * @var Client
     */
    public Client $httpClient;

    function __construct(OptimetaCitationsPlugin $plugin)
    {
        $this->plugin = $plugin;

        $this->httpClient = new Client([
            'headers' => [
                'User-Agent' => Application::get()->getName() . '/' . $this->plugin->getDisplayName(),
                'Accept' => 'application/json'],
            'verify' => false]);
    }

    /**
     * Gets json object from API and returns the body of the response as array
     *
     * @param string $orcid
     * @return array
     */
    public function getObjectFromApi(string $orcid): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->url . '/' . $orcid);

            if ($response->getStatusCode() != 200) return [];

            $orcidObject = json_decode($response->getBody(), true);

            if (empty($orcidObject) || json_last_error() !== JSON_ERROR_NONE) return [];

            return $orcidObject;
        } catch (GuzzleException|\Exception $ex) {
            error_log($ex->getMessage());
        }

        return [];
    }
}
