<?php
namespace Optimeta\Shared;

use GuzzleHttp\Client;

class OptimetaBase
{
    /**
     * @desc User agent name to identify us
     * @var string
     */
    protected $userAgent = 'OJS Optimeta Plugin';

    /**
     * @desc The url to the api
     * @var string
     */
    protected $url = '';

    /**
     * @desc Access username
     * @var string
     */
    protected $username = '';

    /**
     * @desc Access password
     * @var string
     */
    protected $password = '';

    /**
     * @desc Access token
     * @var string
     */
    protected $token = '';

    /**
     * @desc GuzzleHttp object
     * @var object (class)
     */
    protected $client;

    /**
     * @desc Contains the last error
     * @var string
     */
    protected $lastError;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @desc Sets the value for property $userAgent
     * @param string $userAgent
     * @return void
     */
    public function setUserAgent(string $userAgent)
    {
        if(!empty($userAgent)){
            $this->userAgent = $userAgent;
        }
    }

    /**
     * @desc Sets the value of the property $url
     * @param string $url
     * @return void
     */
    public function setUrl(string $url)
    {
        if(!empty($url)){
            $this->url = $url;
        }
    }

    /**
     * @desc Sets the value for property $username
     * @param string $username
     * @return void
     */
    public function setUsername(string $username)
    {
        if(!empty($username)){
            $this->username = $username;
        }
    }

    /**
     * @desc Sets the value for property $password
     * @param string $password
     * @return void
     */
    public function setPassword(string $password)
    {
        if(!empty($password)){
            $this->password = $password;
        }
    }

    /**
     * @desc Sets the value for property $token
     * @param string $token
     * @return void
     */
    public function setToken(string $token)
    {
        if(!empty($token)){
            $this->token = $token;
        }
    }

    /**
     * @desc Returns the last error the script ran into.
     * @returns string
     */
    public function getLastError(): string
    {
        return $this->lastError;
    }
}
