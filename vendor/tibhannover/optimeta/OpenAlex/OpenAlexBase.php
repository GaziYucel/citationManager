<?php
namespace Optimeta\Shared\OpenAlex;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Http\Client\Exception;

use Optimeta\Shared\OpenAlex\Model\Author;
use Optimeta\Shared\OpenAlex\Model\Work;

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

    /**
     * @desc DOI of work
     * @var
     */
    protected $doi;

    /**
     * @desc Author object
     * @var object (class)
     */
    protected $author;

    /**
     * @desc Work object
     * @var object (class)
     */
    protected $work;

    public function __construct(string $doi)
    {
        $this->doi = $doi;
        $this->client = new Client();
        $this->author = new Author();
        $this->work = new Work();
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
     * @return string
     * @throws GuzzleException
     */
    public function getWorkId(): string
    {
        if (empty($this->doi)) return '';
        $response = $this->getWorkFromApiAsObject();
        return $response->id;
    }

    /**
     * @return string
     * @throws GuzzleException
     */
    public function getWorkFromApiAsJson(): string
    {
        if (empty($this->doi)) return '';

        $response = '';

        try{
            $responseRaw = $this->client->request('GET', $this->url . 'works/doi:' . $this->doi);
            $response = $responseRaw->getBody();
        }
        catch(Exception $ex){}

        return $response;
    }

    /**
     * @return object (Work)
     * @throws GuzzleException
     */
    public function getWorkFromApiAsObject(): object
    {
        if (empty($this->doi)) return $this->work;

        try{
            $response = $this->client->request('GET', $this->url . 'works/doi:' . $this->doi);
            $responseBody = $response->getBody();
            $responseBodyArray = json_decode($responseBody, true);
            foreach($responseBodyArray as $key => $value){
                if(property_exists($this->work, $key)){
                    $this->work->$key = $value;
                }
            }
        }
        catch(Exception $ex){}

        return $this->work;
    }
}
