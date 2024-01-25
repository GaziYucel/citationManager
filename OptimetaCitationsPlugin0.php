<?php
/**
 * @file plugins/generic/optimetaCitations/OptimetaCitationsPlugin.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class OptimetaCitationsPlugin
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Plugin for parsing Citations and submitting to Open Access websites.
 */

namespace APP\plugins\generic\optimetaCitations;

require_once(__DIR__ . '/vendor/autoload.php');

use APP\core\Application;
use APP\notification\Notification;
use APP\notification\NotificationManager;
use PKP\config\Config;
use PKP\core\JSONMessage;
use PKP\db\DAO;
use PKP\db\DAORegistry;
use PKP\linkAction\LinkAction;
use PKP\linkAction\request\AjaxAction;
use PKP\linkAction\request\AjaxModal;
use PKP\plugins\GenericPlugin;
use PKP\plugins\Hook;
use APP\plugins\generic\optimetaCitations\classes\Log;
use APP\plugins\generic\optimetaCitations\classes\Components\Forms\SettingsForm;
use APP\plugins\generic\optimetaCitations\classes\Db\CitationsExtendedDAO;
use APP\plugins\generic\optimetaCitations\classes\Db\PluginDAO;
use APP\plugins\generic\optimetaCitations\classes\Db\PluginMigration;
use APP\plugins\generic\optimetaCitations\classes\DataModels\AuthorModel;
use APP\plugins\generic\optimetaCitations\classes\DataModels\WorkModel;
use APP\plugins\generic\optimetaCitations\classes\PID\Doi;
use APP\plugins\generic\optimetaCitations\classes\PID\OpenAlex;
use APP\plugins\generic\optimetaCitations\classes\PID\Orcid;
use APP\plugins\generic\optimetaCitations\classes\PID\Wikidata;

class OptimetaCitationsPlugin0 extends GenericPlugin
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
     * @copydoc Plugin::register()
     *
     * @param null|mixed $mainContextId
     */
    public function register($category, $path, $mainContextId = null): bool
    {
        $success = parent::register($category, $path, $mainContextId);

        if ($success) {
            // If the system isn't installed, or is performing an upgrade, don't
            // register hooks. This will prevent DB access attempts before the
            // schema is installed.
            if (Application::isUnderMaintenance()) {
                return true;
            }

            if ($this->getEnabled($mainContextId)) {
                $this->pluginInitialization();
            }
        }

        return $success;
    }

    /**
     * Helper to register hooks that are used in normal plugin setup and in CLI tool usage.
     */
    private function pluginInitialization()
    {
        // add to publication schema
        Hook::add('Schema::get::publication', array($this, 'addToSchema'));

        $citationsExtendedDAO = new CitationsExtendedDAO();
        DAORegistry::registerDAO('CitationsExtendedDAO', $citationsExtendedDAO);

        $this->pluginDao = new PluginDAO($this);

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
        $schema = &$args[0];
        $pluginDao = new PluginDao($this);
        $pluginDao->addToSchema($schema);
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
//    public function isSitePlugin()
//    {
//        return true;
//    }

    /**
     * Add a settings action to the plugin's entry in the
     * plugins list.
     *
     * @param Request $request
     * @param array $actionArgs
     * @return array
     */
//    public function getActions($request, $actionArgs)
//    {
//        // Get the existing actions
//        $actions = parent::getActions($request, $actionArgs);
//
//        // Only add the settings action when the plugin is enabled
//        if (!$this->getEnabled()) return $actions;
//
//        $router = $request->getRouter();
//
//        $actions[] = new LinkAction(
//            'settings',
//            new AjaxModal(
//                $router->url($request, null, null, 'manage', null,
//                    ['verb' => 'settings', 'plugin' => $this->getName(), 'category' => 'generic']),
//                $this->getDisplayName()
//            ),
//            __('manager.plugins.settings'),
//            null
//        );
//
//        $actions[] = new LinkAction(
//            'initialise_plugin',
//            new AjaxAction(
//                $router->url($request, null, null, 'manage', null,
//                    ['verb' => 'initialise_plugin', 'plugin' => $this->getName(), 'category' => 'generic'])),
//            __('plugins.generic.optimetaCitations.settings.initialise.button'),
//            null);
//
//        $actions[] = new LinkAction(
//            'test_settings',
//            new AjaxAction(
//                $router->url(
//                    $request, null, null, 'manage', null,
//                    array('verb' => 'test_settings', 'plugin' => $this->getName(), 'category' => 'generic'))),
//            __('plugins.generic.optimetaCitations.settings.test.button'),
//            null);
//
//        $actions[] = new LinkAction(
//            'batch_deposit',
//            new AjaxAction(
//                $router->url($request, null, null, 'manage', null,
//                    ['verb' => 'batch_deposit', 'plugin' => $this->getName(), 'category' => 'generic'])),
//            __('plugins.generic.optimetaCitations.settings.deposit.button'),
//            null);
//
//        return $actions;
//    }

    /**
     * Load a form when the `settings` button is clicked and
     * save the form when the user saves it.
     *
     * @param array $args
     * @param Request $request
     * @return JSONMessage
     */
//    public function manage($args, $request)
//    {
//        $context = $request->getContext();
//
//        switch ($request->getUserVar('verb')) {
//            case 'settings':
//                $form = new SettingsForm($this);
//
//                // Fetch and show current data
//                if (!$request->getUserVar('save')) {
//                    $form->initData();
//                    return new JSONMessage(true, $form->fetch($request));
//                }
//
//                // Validate and save the form data
//                $form->readInputData();
//                if ($form->validate()) {
//                    $form->execute();
//                    return new JSONMessage(true);
//                }
//            case 'test_settings':
//                $notificationManager = new NotificationManager();
//                $notificationManager->createTrivialNotification(
//                    Application::get()->getRequest()->getUser()->getId(),
//                    Notification::NOTIFICATION_TYPE_WARNING,
//                    array('contents' => __('plugins.generic.optimetaCitations.not_implemented')));
//                return \DAO::getDataChangedEvent();
//            case 'initialise_plugin':
////                $this->pluginActivationActions();
//                $pluginMigration = new PluginMigration();
////                $pluginMigration->createCitationsExtendedIfNotExists();
//                $notificationManager = new NotificationManager();
//                $notificationManager->createTrivialNotification(
//                    Application::get()->getRequest()->getUser()->getId(),
//                    Notification::NOTIFICATION_TYPE_SUCCESS,
//                    array('contents' => __('plugins.generic.optimetaCitations.settings.initialise.notification')));
//                return DAO::getDataChangedEvent();
//            case 'batch_deposit':
////                $depositor = new DepositorHandler();
////                $depositor->batchDeposit();
//                $notificationManager = new NotificationManager();
//                $notificationManager->createTrivialNotification(
//                    Application::get()->getRequest()->getUser()->getId(),
//                    Notification::NOTIFICATION_TYPE_SUCCESS,
//                    array('contents' => __('plugins.generic.optimetaCitations.settings.deposit.notification')));
//                return DAO::getDataChangedEvent();
//        }
//
//        return parent::manage($args, $request);
//    }

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
//    public function addPublicationStatement($hookName, $params)
//    {
//
//        // Get the publication statement for this journal or press
//        $context = Application::get()->getRequest()->getContext();
//        $contextId = $context ? $context->getId() : Application::CONTEXT_SITE;
//        $publicationStatement = $this->getSetting($contextId, 'publicationStatement');
//
//        // If the journal or press does not have a publication statement,
//        // check if there is one saved for the site.
//        if (!$publicationStatement && $contextId !== Application::CONTEXT_SITE) {
//            $publicationStatement = $this->getSetting(Application::CONTEXT_SITE, 'publicationStatement');
//        }
//
//        // Do not modify the output if there is no publication statement
//        if (!$publicationStatement) {
//            return false;
//        }
//
//        // Add the publication statement to the output
//        $output = &$params[2];
//        $output .= '<p class="publication-statement">' . PKPString::stripUnsafeHtml($publicationStatement) . '</p>';
//
//        return false;
//    }
}

// For backwards compatibility -- expect this to be removed approx. OJS/OMP/OPS 3.6
if (!PKP_STRICT_MODE) {
    class_alias('\APP\plugins\generic\optimetaCitations\OptimetaCitationsPlugin0', '\OptimetaCitationsPlugin0');
}
