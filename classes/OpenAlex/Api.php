<?php
/**
 * @file plugins/generic/optimetaCitations/classes/OpenAlex/Api.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class OpenAlexBase
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief OpenAlex Api class
 */

namespace APP\plugins\generic\optimetaCitations\classes\OpenAlex;

use APP\core\Application;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Api
{
    /**
     * @var string
     */
    public string $userAgent = OPTIMETA_CITATIONS_PLUGIN_NAME;

    /**
     * @var string
     */
    public string $url = 'https://api.openalex.org';

    /**
     * @var object
     */
    public object $httpClient;


    public function __construct(?string $url = '')
    {
        $this->userAgent = Application::get()->getName() . '/' . $this->userAgent;

        if (!empty($url)) $this->url = $url;

        $this->httpClient = new Client(
            [
                'headers' => [
                    'User-Agent' => $this->userAgent,
                    'Accept' => 'application/json'],
                'verify' => false
            ]
        );
    }

    /**
     * Gets json object from API and returns the body of the response as array
     *
     * @param string $doi
     * @return array
     */
    public function getWork(string $doi): array
    {
        try {
            $response = $this->httpClient->request(
                'GET',
                $this->url . '/' . 'works/doi:' . $doi
            );

            if ($response->getStatusCode() != 200) return [];

            $result = json_decode($response->getBody(), true);
            if (empty($result) || json_last_error() !== JSON_ERROR_NONE) return [];

            return $result;

        } catch (GuzzleException|\Exception $ex) {
            error_log($ex->getMessage());
        }

        return [];
    }
}
