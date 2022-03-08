<?php
namespace Optimeta\Shared\Wikidata;

class Base
{
    /**
     * @desc User agent name to identify our bot
     * @var string
     */
    protected $userAgent = 'OJS Plugin Optimeta';

    /**
     * @desc The bots username
     * @var string
     */
    protected $username = '';

    /**
     * @desc The bots password
     * @var string
     */
    protected $password = '';

    /**
     * @desc Whether the bot is logged in
     * @var bool
     */
    protected $isLoggedIn = false;

    /**
     * @desc Token for editing (see http://www.mediawiki.org/wiki/Manual:Edit_token)
     * @var string
     */
    protected $token = '';

    /**
     * @desc The url to the api
     * @var string
     */
    protected $url = 'https://www.wikidata.org/w/api.php';

    /**
     * @desc Contains the last error the bot had.
     * @var string
     */
    protected $lastError;

    /**
     * @desc GuzzleHttp object
     * @var object (class)
     */
    protected $httpClient;

    public function __construct()
    {
        $this->httpClient = new \GuzzleHttp\Client();
    }

    /**
     * @desc Logs the account.
     * @param string $username - The account's username.
     * @param string $password - The account's password.
     * @returns bool - Returns true on success, false on failure.
     */
    protected function login(string $username, string $password): bool
    {
        $post = array(
            'lgname' => $username,
            'lgpassword' => $password
        );

        while (true) {
            $return = $this->query(array('action' => 'login'), $post);
            var_dump($return);
            if ($return['login']['result'] == 'Success') {
                $this->isLoggedIn = true;
                return true;
            } elseif ($return['login']['result'] == 'NeedToken') {
                $post['lgtoken'] = $return['login']['token'];
            } else {
                $this->lastError = $return['login']['code'];
                return false;
            }
        }
    }

    /**
     * @desc Logs the account out of the wiki and destroys all their session data.
     */
    protected function logout()
    {
        $this->query(array('action' => 'logout'));
        $this->token = null;
        $this->lastError = null;
        $this->isLoggedIn = false;
    }

    /**
     * @desc Returns edit token.
     * @param bool (default=false) $force - Force the script to get a fresh edit token.
     * @returns mixed - Returns the account's token on success or false on failure.
     */
    protected function getToken($force = false)
    {
        if ($this->token != null && $force == false)
            return $this->token;
        $x = $this->query(array('action' => 'query', 'meta' => 'tokens'));
        return $x['query']['tokens']['csrftoken'];
    }

    /**
     * @desc Returns the last error the script ran into.
     * @returns string
     */
    protected function getLastError(): string
    {
        return $this->lastError;
    }

    /**
     * @desc Query API with get/post and return as array
     * @param $query
     * @param $post
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function query($query, $post = null): array
    {
        $query = $this->queryString($query);
        if ($post == null)
            $data = $this->httpClient->get($this->url . $query);
        else
            $data = $this->httpClient->post($this->url . $query, $post);
        return json_decode($data, true);
    }

    /**
     * @desc Constructs querystring and returns as urlencoded string
     * @param array $query
     * @return string
     */
    protected function queryString(array $query): string
    {
        $return = "?format=json";
        foreach ($query as $key => $value) {
            $return .= "&" . urlencode($key) . "=" . urlencode($value);
        }
        return $return;
    }
}
