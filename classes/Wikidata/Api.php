<?php
/**
 * @file plugins/generic/optimetaCitations/classes/Wikidata/Api.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Api
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Api class
 */

namespace APP\plugins\generic\optimetaCitations\classes\Wikidata;

use APP\core\Application;
use APP\plugins\generic\optimetaCitations\classes\Helpers\LogHelper;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Api
{
    /**
     * @var string
     */
    public string $userAgent = OPTIMETA_CITATIONS_PLUGIN_NAME;

    /**
     * @var string
     */
    public string $url = 'https://www.wikidata.org/w/api.php';

    /**
     * @var string
     */
    public string $username;

    /**
     * @var string
     */
    public string $password;

    /**
     * @var object
     */
    public object $httpClient;

    /**
     * Whether the client is logged in
     *
     * @var bool
     */
    public bool $isLoggedIn = false;

    /**
     * Login token
     *
     * @var string
     */
    public string $loginToken;

    /**
     * CSRF token
     *
     * @var string
     */
    public string $csrfToken;

    /**
     * Maximum number of login attempts
     * @var int
     */
    public int $maxLoginAttempts = 3;

    function __construct(?string $username = '', ?string $password = '', ?string $url = '')
    {
        $this->userAgent = Application::get()->getName() . '/' . $this->userAgent;

        if (!empty($username)) $this->username = $username;
        if (!empty($password)) $this->password = $password;
        if (!empty($url)) $this->url = $url;

        $this->httpClient = new Client(
            [
                'headers' => [
                    'User-Agent' => $this->userAgent,
                    'Accept' => 'application/json'],
                'verify' => false,
                'cookies' => true
            ]
        );

        // login if username/password provided; else proceed anonymously
        if (!empty($this->username) && !empty($this->password)) {
            $this->loginToken = $this->getLoginToken();
            $this->isLoggedIn = $this->login();
            $this->csrfToken = $this->getCsrfToken();
        }
    }

    /**
     * Logs in the account.
     *
     * Example response {"login":{"result":"Success","lguserid":6649,"lgusername":"YucelGazi"}}
     * @return bool
     */
    public function login(): bool
    {
        $action = 'login';
        $query = ['lgname' => $this->username];
        $form = [
            'lgname' => $this->username,
            'lgpassword' => $this->password,
            'lgtoken' => $this->loginToken];

        $response = $this->actionPost($action, $query, $form);

        if (empty($response)) return false;

        if (!empty($response['login']['result']) && strtolower($response['login']['result']) == 'success')
            return true;

        return false;
    }

    /**
     * Logs the account out of the wiki and destroys all their session data.
     *
     * @return bool
     */
    public function logout(): bool
    {
        if (!empty($this->username) && !empty($this->loginToken)) return false;

        $action = 'logout';
        $query = ['lgname' => $this->username];
        $form = ['token' => $this->csrfToken];

        $response = $this->actionPost($action, $query, $form);

        if ($response == '{}') return true;

        return false;
    }

    /**
     * Gets and sets the (login) token.
     *
     * @return string
     */
    public function getLoginToken(): string
    {
        $action = 'query';
        $query = [
            'meta' => 'tokens',
            'type' => 'login'];

        $response = $this->actionGet($action, $query);

        if (empty($response)) return '';

        if (!empty($response['query']['tokens']['logintoken']))
            return $response['query']['tokens']['logintoken'];

        return '';
    }

    /**
     * Gets and sets the (csrf) token.
     *
     * @return string
     */
    public function getCsrfToken(): string
    {
        $action = 'query';
        $query = [
            'meta' => 'tokens',
            'type' => 'csrf'];

        $response = $this->actionGet($action, $query);

        if (empty($response)) return '';

        if (!empty($response['query']['tokens']['csrftoken']))
            return $response['query']['tokens']['csrftoken'];

        return '';
    }

    /**
     * Execute action (GET) against API and returns the body
     *
     * @param string $action
     * @param array|null $query
     * @return array
     */
    public function actionGet(string $action, ?array $query = null): array
    {
        $query['action'] = $action;
        $query['format'] = 'json';
        $query['formatversion'] = '2';

        try {
            $response = $this->httpClient->request(
                'GET',
                $this->url . '?' . http_build_query($query)
            );

//            LogHelper::logInfo(
//                '[location: Wikidata::API::actionGet]' .
//                '[statusCode: ' . $response->getStatusCode() . ']' .
//                '[userAgent: ' . $this->userAgent . ']' .
//                '[url: ' . $this->url . '?' . http_build_query($query) . ']'
//            );

            if ($response->getStatusCode() != 200) return [];

            $result = json_decode($response->getBody(), true);
            if (empty($result) || json_last_error() !== JSON_ERROR_NONE) return [];

            return $result;

        } catch (GuzzleException|Exception $ex) {
            error_log($ex->getMessage());
        }

        return [];
    }

    /**
     * Execute action (POST) against API and returns the body
     *
     * @param string $action
     * @param array|null $query
     * @param array|null $form
     * @return array
     */
    public function actionPost(string $action, ?array $query = null, ?array $form = null): array
    {
        $query['action'] = $action;
        $query['format'] = 'json';
        $query['formatversion'] = '2';

        if (!empty($this->csrfToken)) $form['token'] = $this->csrfToken;

        try {
            $response = $this->httpClient->request(
                'POST',
                $this->url . '?' . http_build_query($query),
                [
                    'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
                    'form_params' => $form
                ]
            );

//            LogHelper::logInfo(
//                '[location: Wikidata::API::actionPost]' .
//                '[statusCode: ' . $response->getStatusCode() . ']' .
//                '[userAgent: ' . $this->userAgent . ']' .
//                '[url: ' . $this->url . '?' . http_build_query($query) . ']'
//            );

            if ($response->getStatusCode() != 200) return [];

            $result = json_decode($response->getBody(), true);
            if (empty($result) || json_last_error() !== JSON_ERROR_NONE) return [];

            return $result;

        } catch (GuzzleException|Exception $ex) {
            error_log($ex->getMessage());
        }

        return [];
    }

}
