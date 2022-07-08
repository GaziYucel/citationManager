<?php
namespace Optimeta\Shared\OpenAlex;

use GuzzleHttp\Exception\GuzzleException;
use Optimeta\Shared\OpenAlex\Model\Work;
use Optimeta\Shared\OptimetaBase;

class OpenAlexBase extends OptimetaBase
{
    /**
     * @desc The url to the api
     * @var string
     */
    protected $baseUrl = 'https://api.openalex.org/';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $doi
     * @return object (Work)
     * @throws GuzzleException
     */
    public function getWorkFromApiAsObjectWithDoi($doi): object
    {
        $work = new Work();

        if (empty($doi)) return $work;

        try{
            $response = $this->client->request('GET', $this->baseUrl . 'works/doi:' . $doi);
            $responseBody = $response->getBody();
            $responseBodyArray = json_decode($responseBody, true);


            foreach($responseBodyArray as $key => $value){
                if(property_exists($work, $key)){
                    $work->$key = $value;
                }
            }
        }
        catch(\Exception $ex){}

        return $work;
    }
}
