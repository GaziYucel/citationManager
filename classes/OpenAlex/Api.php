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

use Exception;
use APP\plugins\generic\optimetaCitations\OptimetaCitationsPlugin;

class Api extends \APP\plugins\generic\optimetaCitations\classes\Api
{
    public function __construct(
        OptimetaCitationsPlugin $plugin, string $url,
        ?string                 $username = '', ?string $password = '', ?array $httpClientOptions = [])
    {
        parent::__construct($plugin, $url, $username, $password, $httpClientOptions);
    }

    /**
     * Gets json object from API and returns the body of the response as array
     *
     * @param string $id
     * @return array
     */
    public function getObjectFromApi(string $id): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->url . 'works/doi:' . $id);

            if ($response->getStatusCode() != 200) return [];

            return json_decode($response->getBody(), true);
        } catch (\GuzzleHttp\Exception\GuzzleException|Exception $ex) {
            error_log($ex->getMessage(), true);
        }

        return [];
    }
}
