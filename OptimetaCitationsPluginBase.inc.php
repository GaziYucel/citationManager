<?php
/**
 * @file plugins/generic/optimetaCitations/OptimetaCitationsPluginBase.inc.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class OptimetaCitationsPluginBase
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Plugin for parsing Citations and submitting to Open Access websites.
 */

const OptimetaCitations_ParsedKeyDb     = 'OptimetaCitations__CitationsParsed';
const OptimetaCitations_ParsedKeyForm   = 'OptimetaCitations__CitationsParsed';
const OptimetaCitations_ApiEndpoint     = 'OptimetaCitations';
const OptimetaCitations_PublicationForm = "OptimetaCitations_PublicationForm";

// require_once ( __DIR__ . '/vendor/autoload.php');

import('lib.pkp.classes.plugins.GenericPlugin');
import('lib.pkp.classes.site.VersionCheck');

class OptimetaCitationsPluginBase extends GenericPlugin
{
    protected $citationsKeyDb   = OptimetaCitations_ParsedKeyDb;
    protected $citationsKeyForm = OptimetaCitations_ParsedKeyForm;
    protected $apiEndpoint      = OptimetaCitations_ApiEndpoint;

    protected $version = '0.0.0.0';

    protected $apiBaseUrl = '';

    protected $templateParameters = [
            'pluginStylesheetURL' => '',
            'pluginJavaScriptURL' => '',
            'pluginImagesURL' => '',
            'pluginApiParseUrl' => '',
            'pluginApiEnrichUrl' => '',
            'pluginApiSubmitUrl' => '',
            'citationsKeyForm' => ''
        ];

    /**
     * @copydoc Plugin::register
     */
    public function register($category, $path, $mainContextId = null)
    {
        // Register the plugin even when it is not enabled
        $success = parent::register($category, $path);

        // Get and store OJS version
        $this->version = VersionCheck::getCurrentCodeVersion()->getVersionString(false);

        // Current Request / Context
        $request = $this->getRequest();
        $context = $request->getContext();
        $dispatcher = $request->getDispatcher();
        $this->apiBaseUrl = $dispatcher->url($request, ROUTE_API, $context->getData('urlPath'), '');

        // Fill generic template parameters
        $this->templateParameters = [
            'pluginStylesheetURL' => $this->getStylesheetUrl($request),
            'pluginJavaScriptURL' => $this->getJavaScriptURL($request),
            'pluginImagesURL' => $this->getImagesURL($request),
            'pluginApiParseUrl' => $this->apiBaseUrl . $this->apiEndpoint . '/parse',
            'pluginApiEnrichUrl' => $this->apiBaseUrl . $this->apiEndpoint . '/enrich',
            'pluginApiSubmitUrl' => $this->apiBaseUrl . $this->apiEndpoint . '/submit',
            'citationsKeyForm' => $this->citationsKeyForm
        ];

        if ($success && $this->getEnabled())
        {
            // Is triggered with every request from anywhere
            HookRegistry::register('Schema::get::publication', array($this, 'addToSchema'));
        }

        return $success;
    }

    /**
     * Add a property to the publication schema
     *
     * @param $hookName string `Schema::get::publication`
     * @param $args [[
     * @option object Publication schema
     */
    public function addToSchema(string $hookName, array $args): void
    {
        $schema = $args[0];

        $properties = '{
            "type": "string",
            "multilingual": false,
            "apiSummary": true,
            "validation": [ "nullable" ]
        }';

        $schema->properties->{$this->citationsKeyDb} = json_decode($properties);
    }

    /**
     * Get the JavaScript URL for this plugin.
     */
    function getJavaScriptURL($request): string
    {
        return $request->getBaseUrl() . '/' . $this->getPluginPath() . '/js';
    }

    /**
     * Get the Images URL for this plugin.
     */
    function getImagesURL($request): string
    {
        return $request->getBaseUrl() . '/' . $this->getPluginPath() . '/images';
    }

    /**
     * Get the Stylesheet URL for this plugin.
     */
    function getStylesheetUrl($request): string
    {
        return $request->getBaseUrl() . '/' . $this->getPluginPath() . '/css';
    }

    /**
     * @copydoc PKPPlugin::getDisplayName
     */
    public function getDisplayName(): string
    {
        return __('plugins.generic.optimetaCitationsPlugin.name');
    }

    /**
     * @copydoc PKPPlugin::getDescription
     */
    public function getDescription(): string
    {
        return __('plugins.generic.optimetaCitationsPlugin.description');
    }
}
