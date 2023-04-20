<?php

namespace Optimeta\Shared\OpenAlex;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Optimeta\Shared\OpenAlex\Model\Work;

class OpenAlexBase
{
    /**
     * User agent name to identify us
     * @var string
     */
    protected string $userAgent = 'OJSOptimetaCitations';

    /**
     * @desc The url to the api
     * @var string
     */
    protected string $url = 'https://api.openalex.org/';

    /**
     * @desc GuzzleHttp\Client
     * @var object (class)
     */
    protected object $client;

    public function __construct()
    {
        if (!empty(OPTIMETA_CITATIONS_USER_AGENT))
            $this->userAgent = OPTIMETA_CITATIONS_USER_AGENT;

        $this->client = new Client([
            'headers' => ['User-Agent' => $this->userAgent],
            'verify' => false
        ]);
    }

    /**
     * Get Work from OpenAlex Api
     * @param $doi
     * @return Work
     */
    public function getWorkFromApiAsObjectWithDoi($doi): Work
    {
        $work = new Work();

        if (empty($doi)) return $work;

        try {
            $response = $this->client->request('GET', $this->url . 'works/doi:' . $doi);
            $responseBody = $response->getBody();
            $responseBodyArray = json_decode($responseBody, true);

            foreach ($responseBodyArray as $key => $value) {
                if (property_exists($work, $key)) {
                    $work->$key = $value;
                }
            }
        } catch (GuzzleException|\Exception $ex) {
            error_log($ex->getMessage(), true);
        }

        return $work;
    }
}
