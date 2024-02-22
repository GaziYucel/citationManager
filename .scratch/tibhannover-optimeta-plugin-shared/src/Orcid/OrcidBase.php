<?php
/**
 * @file plugins/generic/citationManager/vendor/tibhannover/optimeta/src/Orcid/OrcidBase.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class OrcidBase
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief OrcidBase class
 */

namespace Optimeta\Shared\Orcid;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Optimeta\Shared\Orcid\Model\Author;

class OrcidBase
{
    /**
     * User agent name to identify us
     * @var string
     */
    protected string $userAgent = 'OJSOptimetaCitations';

    /**
     * The url to the api
     * @var string
     */
    protected string $url = 'https://pub.orcid.org/v2.1/';

    /**
     * GuzzleHttp\Client
     * @var object (class)
     */
    protected object $client;

    public function __construct()
    {
        if (!empty(OPTIMETA_CITATIONS_USER_AGENT))
            $this->userAgent = OPTIMETA_CITATIONS_USER_AGENT;

        $this->client = new Client([
            'headers' => [
                'User-Agent' => $this->userAgent,
                'Accept' => 'application/json'],
            'verify' => false
        ]);
    }

    /**
     * @param $orcid
     * @return Author
     */
    public function getAuthorFromApi($orcid): Author
    {
        $author = new Author();

        if (empty($orcid)) return $author;

        try {
            $response = $this->client->request('GET', $this->url . $orcid);

            if (empty($response)) return $author;

            $responseBody = json_decode($response->getBody(), true);

            $author->orcid = $orcid;

            if (!empty($responseBody['person']['name']['given-names']['value']))
                $author->given_name = $responseBody['person']['name']['given-names']['value'];

            if (!empty($responseBody['person']['name']['family-name']['value']))
                $author->family_name = $responseBody['person']['name']['family-name']['value'];

        } catch (GuzzleException|\Exception $ex) {
            error_log($ex->getMessage(), true);
        }

        return $author;
    }
}