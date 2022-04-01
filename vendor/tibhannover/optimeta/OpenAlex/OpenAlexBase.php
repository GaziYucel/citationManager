<?php
namespace Optimeta\Shared\OpenAlex;

use GuzzleHttp\Exception\GuzzleException;
use Http\Client\Exception;

class OpenAlexBase
{
    /**
     * @desc User agent name to identify our bot
     * @var string
     */
    protected $userAgent = 'OJS Optimeta Plugin';

    /**
     * @desc The url to the api
     * @var string
     */
    protected $url = 'https://api.openalex.org/';

    /**
     * @desc Contains the last error the bot had.
     * @var string
     */
    protected $lastError;

    /**
     * @desc GuzzleHttp object
     * @var object (class)
     */
    protected $client;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
    }

    /**
     * @desc Returns the last error the script ran into.
     * @returns string
     */
    public function getLastError(): string
    {
        return $this->lastError;
    }

    /**
     * @param string $doi
     * @return string
     * @throws GuzzleException
     */
    public function getWorkId(string $doi): string
    {
        if (empty($doi)) return '';
        $id = '';

        try{
            $response = $this->client->request('GET', $this->url . 'works/doi:' . $doi);
            $responseBody = $response->getBody();
            $responseBodyArray = json_decode($responseBody, true);
            $id = $responseBodyArray['id'];
            if($id === null) $id = '';
            $id = str_replace('https://openalex.org/', '', $id);
        }
        catch(Exception $ex){}

        return $id;
    }
}
