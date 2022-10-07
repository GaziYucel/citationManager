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
    protected $url;

    /**
     * @desc Access username
     * @var string
     */
    protected $username;

    /**
     * @desc Access password
     * @var string
     */
    protected $password;

    /**
     * @desc Access token
     * @var string
     */
    protected $token;

    /**
     * @desc CSRF token
     * @var string
     */
    protected $csrf;

    /**
     * @desc GuzzleHttp object
     * @var object (class)
     */
    protected $client;

    /**
     * @desc Contains the errors
     * @var string
     */
    protected $errors;

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
        $this->userAgent = $userAgent;
    }

    /**
     * @desc Sets the value of the property $url
     * @param string $url
     * @return void
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    /**
     * @desc Gets the value of the property $url
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @desc Sets the value for property $username
     * @param string $username
     * @return void
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    /**
     * @desc Gets the value for property $username
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @desc Sets the value for property $password
     * @param string $password
     * @return void
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @desc Gets the value for property $password
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @desc Sets the value for property $token
     * @param string $token
     * @return void
     */
    public function setToken(string $token)
    {
        $this->token = $token;
    }

    /**
     * @desc Gets the value for property $token
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @desc Sets the value for property $csrf
     * @param string $csrf
     * @return void
     */
    public function setCsrf(string $csrf)
    {
        $this->csrf = $csrf;
    }

    /**
     * @desc Gets the value for property $csrf
     * @return string|null
     */
    public function getCsrf(): ?string
    {
        return $this->csrf;
    }

    /**
     * @desc Returns the errors the script ran into.
     * @returns string
     */
    public function getErrors(): string
    {
        return $this->errors;
    }
}
