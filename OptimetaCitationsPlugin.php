<?php

/**
 * @file PluginTemplatePlugin.php
 *
 * Copyright (c) 2017-2023 Simon Fraser University
 * Copyright (c) 2017-2023 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class PluginTemplatePlugin
 * @brief Plugin class for the PluginTemplate plugin.
 */

namespace APP\plugins\generic\optimetaCitations;

require_once(__DIR__ . '/vendor/autoload.php');

use APP\core\Application;
use APP\facades\Repo;
use APP\notification\Notification;
use APP\notification\NotificationManager;
use APP\plugins\generic\optimetaCitations\classes\Components\Forms\PublicationForm;
use APP\plugins\generic\optimetaCitations\classes\Components\Forms\SettingsForm;
use APP\plugins\generic\optimetaCitations\classes\Db\CitationsExtendedDAO;
use APP\plugins\generic\optimetaCitations\classes\Db\PluginDAO;
use APP\plugins\generic\optimetaCitations\classes\Frontend\Article;
use APP\plugins\generic\optimetaCitations\classes\Handler\DepositorHandler;
use APP\plugins\generic\optimetaCitations\classes\Handler\PluginAPIHandler;
use APP\plugins\generic\optimetaCitations\classes\Install\OptimetaCitationsMigration;
use APP\plugins\generic\optimetaCitations\classes\Model\AuthorModel;
use APP\plugins\generic\optimetaCitations\classes\Model\WorkModel;
use APP\plugins\generic\optimetaCitations\classes\PID\Doi;
use APP\plugins\generic\optimetaCitations\classes\PID\OpenAlex;
use APP\plugins\generic\optimetaCitations\classes\PID\Orcid;
use APP\plugins\generic\optimetaCitations\classes\PID\Wikidata;
use APP\template\TemplateManager;
use PKP\core\EntityDAO;
use PKP\core\JSONMessage;
use PKP\core\PKPApplication;
use PKP\db\DAO;
use PKP\db\DAORegistry;
use PKP\db\SchemaDAO;
use PKP\linkAction\LinkAction;
use PKP\linkAction\request\AjaxAction;
use PKP\linkAction\request\AjaxModal;
use PKP\plugins\GenericPlugin;
use PKP\plugins\Hook;
use PKP\submission\PKPSubmission;

class OptimetaCitationsPlugin extends GenericPlugin
{
    public const OPTIMETA_CITATIONS_PLUGIN_NAME = 'OptimetaCitationsPlugin';
    public const OPTIMETA_CITATIONS_IS_PRODUCTION_KEY = 'OptimetaCitations_IsProductionEnvironment';
    public const OPTIMETA_CITATIONS_PLUGIN_PATH = __DIR__;
    public const OPTIMETA_CITATIONS_USER_AGENT = 'OJSOptimetaCitations';
    public const OPTIMETA_CITATIONS_API_ENDPOINT = 'OptimetaCitations';
    public const OPTIMETA_CITATIONS_FRONTEND_SHOW_STRUCTURED = 'OptimetaCitations_FrontendShowStructured';
    public const OPTIMETA_CITATIONS_PUBLICATION_WORK = 'OptimetaCitations_PublicationWork';
    public const OPTIMETA_CITATIONS_FORM_NAME = 'OptimetaCitations_PublicationForm';
    public const OPTIMETA_CITATIONS_FORM_FIELD_PARSED = 'OptimetaCitations_CitationsParsed';
    public const OPTIMETA_CITATIONS_SAVED_IS_ENABLED = 'OptimetaCitations_IsEnabled';
    public const OPTIMETA_CITATIONS_WIKIDATA_USERNAME = 'OptimetaCitations_Wikidata_Username';
    public const OPTIMETA_CITATIONS_WIKIDATA_PASSWORD = 'OptimetaCitations_Wikidata_Password';
    public const OPTIMETA_CITATIONS_OPEN_CITATIONS_OWNER = 'OptimetaCitations_Open_Citations_Owner';
    public const OPTIMETA_CITATIONS_OPEN_CITATIONS_REPOSITORY = 'OptimetaCitations_Open_Citations_Repository';
    public const OPTIMETA_CITATIONS_OPEN_CITATIONS_TOKEN = 'OptimetaCitations_Open_Citations_Token';

    /**
     * @copydoc GenericPlugin::register()
     */
    public function register($category, $path, $mainContextId = NULL)
    {
        $success = parent::register($category, $path);

        if ($success && $this->getEnabled()) {
            // Display the publication statement on the article details page
            Hook::add('Templates::Article::Main', [$this, 'addPublicationStatement']);

            $request = $this->getRequest();
            $objOrcid = new Orcid();
            $objWikidata = new Wikidata();
            $objOpenAlex = new OpenAlex();
            $objDoi = new Doi();

            $templateParameters = [
                'customScript' => '',
                'pluginStylesheetURL' => $request->getBaseUrl() . '/' . $this->getPluginPath() . '/css',
                'pluginJavaScriptURL' => $request->getBaseUrl() . '/' . $this->getPluginPath() . '/js',
                'pluginImagesURL' => $request->getBaseUrl() . '/' . $this->getPluginPath() . '/images',
                'pluginApiUrl' => '',
                'isPublished' => 'false',
                'authorModel' => json_encode(get_object_vars(new AuthorModel())),
                'workModel' => json_encode(get_object_vars(new WorkModel())),
                'publicationWork' => '',
                'statusCodePublished' => 3,
                'openAlexURL' => $objOpenAlex->prefix,
                'wikidataURL' => $objWikidata->prefix,
                'orcidURL' => $objOrcid->prefix,
                'doiURL' => $objDoi->prefix];

            $this->pluginDao = new PluginDAO($this);
        }

        return $success;
    }

    /**
     * Provide a name for this plugin
     *
     * The name will appear in the Plugin Gallery where editors can
     * install, enable and disable plugins.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return __('plugins.generic.optimetaCitations.displayName');
    }

    /**
     * Provide a description for this plugin
     *
     * The description will appear in the Plugin Gallery where editors can
     * install, enable and disable plugins.
     *
     * @return string
     */
    public function getDescription()
    {
        return __('plugins.generic.optimetaCitations.description');
    }

    /**
     * Enable the settings form in the site-wide plugins list
     *
     * @return boolean
     */
    public function isSitePlugin()
    {
        return true;
    }

    /**
     * Add a settings action to the plugin's entry in the
     * plugins list.
     *
     * @param Request $request
     * @param array $actionArgs
     * @return array
     */
    public function getActions($request, $actionArgs)
    {
        // Get the existing actions
        $actions = parent::getActions($request, $actionArgs);

        // Only add the settings action when the plugin is enabled
        if (!$this->getEnabled()) return $actions;

//        $linkAction = [];

        $router = $request->getRouter();

        $linkAction = new LinkAction(
            'settings',
            new AjaxModal(
                $router->url($request, null, null, 'manage', null,
                    ['verb' => 'settings', 'plugin' => $this->getName(), 'category' => 'generic']),
                $this->getDisplayName()
            ),
            __('manager.plugins.settings'),
            null
        );
        $actions[] = $linkAction;

        $linkAction = new LinkAction(
            'initialise_plugin',
            new AjaxAction(
                $router->url($request, null, null, 'manage', null,
                    ['verb' => 'initialise_plugin', 'plugin' => $this->getName(), 'category' => 'generic'])),
            __('plugins.generic.optimetaCitations.settings.initialise.button'),
            null);
        $actions[] = $linkAction;

        $linkAction = new LinkAction(
            'batch_deposit',
            new AjaxAction(
                $router->url($request, null, null, 'manage', null,
                    ['verb' => 'batch_deposit', 'plugin' => $this->getName(), 'category' => 'generic'])),
            __('plugins.generic.optimetaCitations.settings.deposit.button'),
            null);
        $actions[] = $linkAction;

        return $actions;
    }

    /**
     * Load a form when the `settings` button is clicked and
     * save the form when the user saves it.
     *
     * @param array $args
     * @param Request $request
     * @return JSONMessage
     */
    public function manage($args, $request)
    {
        switch ($request->getUserVar('verb')) {
            case 'settings':

                // Load the custom form
                $form = new SettingsForm($this);

                // Fetch the form the first time it loads, before
                // the user has tried to save it
                if (!$request->getUserVar('save')) {
                    $form->initData();
                    return new JSONMessage(true, $form->fetch($request));
                }

                // Validate and save the form data
                $form->readInputData();
                if ($form->validate()) {
                    $form->execute();
                    return new JSONMessage(true);
                }
        }

        return parent::manage($args, $request);
    }

    /**
     * Add the publication statement to the article details page.
     *
     * @param string $hookName string
     * @param array $params [[
     * @option array Additional parameters passed with the hook
     * @option TemplateManager
     * @option string The HTML output
     * ]]
     * @return boolean
     */
    public function addPublicationStatement($hookName, $params)
    {

        // Get the publication statement for this journal or press
        $context = Application::get()->getRequest()->getContext();
        $contextId = $context ? $context->getId() : Application::CONTEXT_SITE;
        $publicationStatement = $this->getSetting($contextId, 'publicationStatement');

        // If the journal or press does not have a publication statement,
        // check if there is one saved for the site.
        if (!$publicationStatement && $contextId !== Application::CONTEXT_SITE) {
            $publicationStatement = $this->getSetting(Application::CONTEXT_SITE, 'publicationStatement');
        }

        // Do not modify the output if there is no publication statement
        if (!$publicationStatement) {
            return false;
        }

        // Add the publication statement to the output
        $output = &$params[2];
        $output .= '<p class="publication-statement">' . PKPString::stripUnsafeHtml($publicationStatement) . '</p>';

        return false;
    }
}

// For backwards compatibility -- expect this to be removed approx. OJS/OMP/OPS 3.6
if (!PKP_STRICT_MODE) {
    class_alias('\APP\plugins\generic\optimetaCitations\OptimetaCitationsPlugin', '\OptimetaCitationsPlugin');
}
