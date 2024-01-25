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
use APP\plugins\generic\optimetaCitations\classes\Helpers\LogHelper;
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
    public string $url = 'https://pub.orcid.org/v2.1';

    /**
     * @var Client
     */
    public Client $httpClient;

    function __construct(?string $url = '')
    {
        $this->userAgent = Application::get()->getName() . '/' . $this->userAgent;

        $this->httpClient = new Client(
            [
                'headers' => [
                    'User-Agent' => $this->userAgent,
                    'Accept' => 'application/json'
                ],
                'verify' => false
            ]
        );
    }

    /**
     * Gets json object from API and returns the body of the response as array
     *
     * @param string $orcid
     * @return array
     */
    public function getFromApi(string $orcid): array
    {
        try {
            $response = $this->httpClient->request(
                'GET',
                $this->url . '/' . $orcid);

            LogHelper::logInfo(
                '[statusCode: ' . $response->getStatusCode() . ']' .
                '[userAgent: ' . $this->userAgent . ']' .
                '[url: ' . $this->url . '/' . $orcid . ']' .
                '[response: ' . json_encode($response, JSON_UNESCAPED_SLASHES) . ']'
            );

            if ($response->getStatusCode() != 200) return [];

            $result = json_decode($response->getBody(), true);
            if (empty($result) || json_last_error() !== JSON_ERROR_NONE) return [];

            return $result;

        } catch (GuzzleException|\Exception $ex) {
            error_log($ex->getMessage());
        }

        return '{}';
    }
}
