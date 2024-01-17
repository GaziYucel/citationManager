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

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use APP\plugins\generic\optimetaCitations\OptimetaCitationsPlugin;

class Api extends \APP\plugins\generic\optimetaCitations\classes\Api
{
    /**
     * Whether the client is logged in
     *
     * @var bool
     */
    protected bool $isLoggedIn = false;

    /**
     * Login token
     *
     * @var string
     */
    protected string $loginToken;

    /**
     * CSRF token
     *
     * @var string
     */
    protected string $csrfToken;

    /**
     * Maximum number of login attempts
     * @var int
     */
    protected int $maxLoginAttempts = 3;

    function __construct(
        OptimetaCitationsPlugin $plugin, string $url,
        ?string                 $username = '', ?string $password = '', ?array $httpClientOptions = [])
    {
        parent::__construct($plugin, $url, $username, $password, array_merge($httpClientOptions, ['cookies' => true]));

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

        $response = json_decode($this->actionPost($action, $query, $form), true);

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

        $response = json_decode($this->actionGet($action, $query), true);

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

        $response = json_decode($this->actionGet($action, $query), true);

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
     * @return string
     */
    public function actionGet(string $action, ?array $query = null): string
    {
        $query['action'] = $action;
        $query['format'] = 'json';
        $query['formatversion'] = '2';

        try {
            $response = $this->httpClient->request('GET', $this->url . '?' . http_build_query($query));

            if (!empty($response) && !empty($response->getBody())) return $response->getBody();

        } catch (GuzzleException|Exception $ex) {
            error_log($ex->getMessage(), true);
        }

        return '';
    }

    /**
     * Execute action (POST) against API and returns the body
     *
     * @param string $action
     * @param array|null $query
     * @param array|null $form
     * @return string
     */
    public function actionPost(string $action, ?array $query = null, ?array $form = null): string
    {
        $query['action'] = $action;
        $query['format'] = 'json';
        $query['formatversion'] = '2';

        if (!empty($this->csrfToken)) $form['token'] = $this->csrfToken;

        error_log('form -> ' . json_encode($form, JSON_UNESCAPED_SLASHES));

        try {
            $response = $this->httpClient->request(
                'POST',
                $this->url . '?' . http_build_query($query), [
                'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
                'form_params' => $form]);

            if (!empty($response) && !empty($response->getBody())) return $response->getBody();

        } catch (GuzzleException|Exception $ex) {
            error_log($ex->getMessage(), true);
        }

        return '';
    }

}
