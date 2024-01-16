<?php
/**
 * @file plugins/generic/optimetaCitations/classes/Enrich/Orcid.php
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
use Exception;

class Api
{
    /**
     * The url to the api
     *
     * @var string
     */
    protected string $url;

    /**
     * GuzzleHttp\Client
     * @var object (class)
     */
    protected object $httpClient;

    public function __construct(string $apiUrl)
    {
        $this->url = $apiUrl;

        $this->httpClient = Application::get()->getHttpClient();
    }

    /**
     * Gets response from API and returns the body of the response
     *
     * @param string $orcid
     * @return array
     */
    public function getOrcidObjectFromApi(string $orcid): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->url . '/' . $orcid,
                [
                    'headers' => [
                        'Accept' => 'application/json'],
                    'verify' => false
                ]);

            if ($response->getStatusCode() != 200) return [];

            return json_decode($response->getBody(), true);
        } catch (\GuzzleHttp\Exception\GuzzleException|Exception $ex) {
            error_log($ex->getMessage(), true);
        }

        return [];
    }
}
