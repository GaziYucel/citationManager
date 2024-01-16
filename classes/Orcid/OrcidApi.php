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
use APP\plugins\generic\optimetaCitations\classes\Orcid\Model\Author;
use Exception;

class OrcidApi
{
    /**
     * The url to the api
     *
     * @var string
     */
    protected string $url;

    public function __construct(string $apiUrl)
    {
        $this->url = $apiUrl;
    }

    /**
     * This method retrieves the Author from the API
     *
     * @param string $orcid
     * @return Author
     */
    public function getAuthorFromApi(string $orcid): Author
    {
        $httpClient = Application::get()->getHttpClient();

        $author = new Author();

        if (empty($orcid)) return $author;

        try {

            $response = $httpClient->request('GET', $this->url . '/' . $orcid,
                [
                    'headers' => [
                        'Accept' => 'application/json'],
                    'verify' => false
                ]);

            if ($response->getStatusCode() != 200) return $author;

            $responseBody = json_decode($response->getBody(), true);

            $author->orcid = $orcid;

            if (!empty($responseBody['person']['name']['given-names']['value']))
                $author->given_name = $responseBody['person']['name']['given-names']['value'];

            if (!empty($responseBody['person']['name']['family-name']['value']))
                $author->family_name = $responseBody['person']['name']['family-name']['value'];

        } catch (\GuzzleHttp\Exception\GuzzleException|Exception $ex) {
            error_log($ex->getMessage(), true);
        }

        return $author;
    }
}
