<?php
namespace Optimeta\Shared\WikiData;

use GuzzleHttp\Exception\GuzzleException;
use Http\Client\Exception;
use Optimeta\Shared\OptimetaBase;

class WikiDataBase extends OptimetaBase
{
    /**
     * @desc Whether the bot is logged in
     * @var bool
     */
    protected $isLoggedIn = false;

    /**
     * @desc The url to the api
     * @var string
     */
    protected $url = 'https://www.wikidata.org/w/api.php';

    public function __construct(?string $url = '', ?string $username = '', ?string $password = '')
    {
        if (!empty($url)) $this->url = $url;
        if (!empty($username)) $this->username = $username;
        if (!empty($password)) $this->password = $password;

        parent::__construct();
    }

    /**
     * @desc Logs the account.
     * @param string $username - The account's username.
     * @param string $password - The account's password.
     * @returns bool - Returns true on success, false on failure.
     */
    public function login()
    {
        $args = [
            'action' => 'login',
            'format' => 'json',
            'lgname' => $this->username,
            'lgpassword' => $this->password,
            'lgtoken' => $this->token
        ];

//        $response = $this->client->request();

        try {
            $response = $this->client->request('POST', $this->url, ['body' => http_build_query($args)]);
            $res['headers'] = json_encode($response->getHeaders());
            $res['body'] = (string)($response->getBody()->getContents());
            return $res;
        } catch (GuzzleException|\Exception $ex) {
        }

        return '--empty--';

//        return $this->actionPost('login', $args);
//        while (true) {
//            $return = $this->actionQueryGet(array('action' => 'login'), $post);
//            var_dump($return);
//            if ($return['login']['result'] == 'Success') {
//                $this->token = $return['login']['token'];
//                $this->isLoggedIn = true;
//                return true;
//            } elseif ($return['login']['result'] == 'NeedToken') {
//                $post['lgtoken'] = $return['login']['token'];
//            } else {
//                $this->errors = $return['login']['code'];
//                return false;
//            }
//        }
    }

    /**
     * @desc Logs the account out of the wiki and destroys all their session data.
     */
    public function logout()
    {
        $args = [
            'action' => 'logout',
            'format' => 'json',
            'token' => $this->csrf
        ];

        try {
            $response = $this->client->request('GET', $this->url . '?' . http_build_query($args));
            $res['headers'] = json_encode($response->getHeaders());
            $res['body'] = (string)($response->getBody()->getContents());
            return $res;
        } catch (GuzzleException|\Exception $ex) {
        }

        return '--empty--';

//        $this->token = null;
//        $this->csrf = null;
//        $this->errors = null;
//        $this->isLoggedIn = false;
    }

    /**
     * @desc Returns login token.
     * @param bool $force (default=false) $force - Force the script to get a fresh edit token.
     */
    public function getLoginToken(bool $force = false)
    {
//        if ($this->token != null && $force === false) return $this->token;

        $args = [
            'meta' => 'tokens',
            'type' => '*'
        ];

        $responseRaw = $this->client;
        $args['action'] = 'query';
        $args['format'] = 'json';

        try {
//            $response = json_decode($this->actionGet('query', $args), true);
            $responseRaw = $this->client->request('GET', $this->url . '?' . http_build_query($args));
            $response = json_decode($responseRaw->getBody(), true);
            if ($response !== null &&
                !empty($response['query']) &&
                !empty($response['query']['tokens']) &&
                !empty($response['query']['tokens']['logintoken'])) {

                $this->token = $response['query']['tokens']['logintoken'];
            }

            $res['headers'] = json_encode($responseRaw->getHeaders());
            $res['body'] = (string)($responseRaw->getBody()->getContents());

            return $res;
        } catch (GuzzleException|\Exception $ex) {
        }

        return '--empty--';

//        return $this->token;
    }

    /**
     * @return array|string
     */
    public function getCsrfToken()
    {
        $args = [
            'meta' => 'tokens',
            'type' => '*'
        ];

        $responseRaw = $this->client;
        $args['action'] = 'query';
        $args['format'] = 'json';

        try {
//            $response = json_decode($this->actionGet('query', $args), true);
            $responseRaw = $this->client->request('GET', $this->url . '?' . http_build_query($args));
            $response = json_decode($responseRaw->getBody(), true);
            if ($response !== null &&
                !empty($response['query']) &&
                !empty($response['query']['tokens']) &&
                !empty($response['query']['tokens']['csrftoken'])) {

                $this->csrf = $response['query']['tokens']['csrftoken'];
            }

            $res['headers'] = json_encode($responseRaw->getHeaders());
            $res['body'] = (string)$responseRaw->getBody()->getContents();
            return $res;
        } catch (GuzzleException|\Exception $ex) {
        }

        return '--empty--';

//        return $this->csrf;
    }

    /**
     * @param array $query
     * @return array|string
     */
    public function getItem(array $query)
    {
        $query['action'] = 'wbgetentities';
        $query['format'] = 'json';
        $queryString = '';
        if (!empty($query)) $queryString = '?' . http_build_query($query);

        try {
            $response = $this->client->request('GET', $this->url . $queryString);
            $res['headers'] = json_encode($response->getHeaders());
            $res['body'] = $response->getBody()->getContents();
            return $res;
        } catch (GuzzleException|\Exception $ex) {
        }

        return '--empty--';
    }

    /**
     * @desc Get entity with action query
     * @param string $searchString
     * @return string
     */
    public function getEntity(string $searchString): string
    {
        if (empty($searchString)) return '';
        $entity = '';
        $query = [
            "prop" => "",
            "list" => "search",
            "srsearch" => $searchString,
            "srlimit" => "2"
        ];

        try {
            $response = json_decode($this->actionQueryGet($query), true);
            if ($response !== null &&
                !empty($response['query']) &&
                !empty($response['query']['search']) &&
                !empty($response['query']['search'][0]) &&
                !empty($response['query']['search'][0]['title'])) {
                $entity = $response['query']['search'][0]['title'];
            }
        } catch (\Exception $ex) {
        }

        return $entity;
    }

    /**
     * * @desc Get doi with action get entities
     * @param string $entity
     * @return string
     */
    public function getDoi(string $entity): string
    {
        if (empty($entity)) return '';
        $doi = '';
        $query = ["ids" => $entity];

        try {
            $response = json_decode($this->actionWbGetEntitiesGet($query), true);
            $doi = $response['entities'][$entity]['claims']['P356'][0]['mainsnak']['datavalue']['value'];
            if ($doi === null) $doi = '';
        } catch (\Exception $ex) {
        }

        return $doi;
    }

    /**
     * @desc Execute action query (GET) against API and return as array
     * @param array $query
     * @return string
     */
    public function actionQueryGet(array $query)
    {
        return $this->actionGet('query', $query);
    }

    /**
     * @desc Execute action wbgetentities (GET) against API and return as array
     * @param array $query
     * @return string
     */
    public function actionWbGetEntitiesGet(array $query)
    {
        return $this->actionGet('wbgetentities', $query);
    }

    /**
     * @desc Execute action (GET) against API and returns the body
     * @param string $action
     * @param array $query
     * @return string
     */
    public function actionGet(string $action, ?array $query = null)
    {
        $response = '';
        $query['action'] = $action;
        $query['format'] = 'json';
        $queryString = '';
        if (!empty($query)) $queryString = '?' . http_build_query($query);

        error_log($queryString);

        try {
            $response = $this->client->request('GET', $this->url . $queryString);
        } catch (GuzzleException|\Exception $ex) {
        }

        if (!empty($response) && !empty($response->getBody())) {
            return $response->getBody();
        }

        return '';
    }

    /**
     * @desc Execute action (POST) against API and returns the body
     * @param string $action
     * @param array|null $query
     * @return \Psr\Http\Message\StreamInterface|string
     */
    public function actionPost(string $action, ?array $query = null)
    {
        $response = '';
        $query['action'] = $action;
        $query['format'] = 'json';
        $queryString = '';
        if (!empty($query)) $queryString = '?' . http_build_query($query);

        error_log($queryString);

        try {
            $response = $this->client->request('POST', $this->url, ['body' => $queryString]);
        } catch (GuzzleException|\Exception $ex) {
        }

        if (!empty($response) && !empty($response->getBody())) {
            return $response->getBody();
        }

        return '';
    }
}
