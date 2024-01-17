<?php
/**
 * @file plugins/generic/optimetaCitations/classes/Api.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Orcid
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Api base class
 */

namespace APP\plugins\generic\optimetaCitations\classes;

use APP\core\Application;
use GuzzleHttp\Client;
use APP\plugins\generic\optimetaCitations\OptimetaCitationsPlugin;

class Api
{
    /**
     * @var OptimetaCitationsPlugin
     */
    public OptimetaCitationsPlugin $plugin;

    /**
     * @var string
     */
    protected string $userAgent;

    /**
     * @var string
     */
    protected string $url;

    /**
     * @var string
     */
    protected string $username;

    /**
     * @var string
     */
    protected string $password;

    /**
     * @var GuzzleHttp\Client
     */
    protected object $httpClient;

    public function __construct(
        OptimetaCitationsPlugin $plugin, string $url,
        ?string                 $username = '', ?string $password = '', ?array $httpClientOptions = [])
    {
        $this->plugin = $plugin;
        $this->url = $url;

        if (!empty($username)) $this->username = $username;

        if (!empty($password)) $this->password = $password;

        $this->httpClient = new Client(
            array_merge([
                'headers' => [
                    'User-Agent' => Application::get()->getName() . '/' . $this->plugin->getDisplayName(),
                    'Accept' => 'application/json'],
                'verify' => false],
                $httpClientOptions));
    }
}
