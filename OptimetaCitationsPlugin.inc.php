<?php
/**
 * @file plugins/generic/optimetaCitations/OptimetaCitationsPlugin.inc.php
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

const OPTIMETA_CITATIONS_IS_TEST_ENVIRONMENT = true;
const OPTIMETA_CITATIONS_PLUGIN_PATH = __DIR__;
const OPTIMETA_CITATIONS_USER_AGENT = 'OJSOptimetaCitations';
const OPTIMETA_CITATIONS_API_ENDPOINT = 'OptimetaCitations';
const OPTIMETA_CITATIONS_PUBLICATION_WORK = 'OptimetaCitations_PublicationWork';
const OPTIMETA_CITATIONS_FORM_NAME = 'OptimetaCitations_PublicationForm';
const OPTIMETA_CITATIONS_FORM_FIELD_PARSED = 'OptimetaCitations_CitationsParsed';
const OPTIMETA_CITATIONS_SAVED_IS_ENABLED = 'OptimetaCitations_IsEnabled';
const OPTIMETA_CITATIONS_OPENALEX_URL = 'https://openalex.org';
const OPTIMETA_CITATIONS_WIKIDATA_USERNAME = 'OptimetaCitations_Wikidata_Username';
const OPTIMETA_CITATIONS_WIKIDATA_PASSWORD = 'OptimetaCitations_Wikidata_Password';
const OPTIMETA_CITATIONS_WIKIDATA_URLS = [
    'prod' => 'https://www.wikidata.org/wiki',
    'test' => 'https://test.wikidata.org/wiki',
    'apiProd' => 'https://www.wikidata.org/w/api.php',
    'apiTest' => 'https://test.wikidata.org/w/api.php'
];
const OPTIMETA_CITATIONS_OPEN_CITATIONS_OWNER = 'OptimetaCitations_Open_Citations_Owner';
const OPTIMETA_CITATIONS_OPEN_CITATIONS_REPOSITORY = 'OptimetaCitations_Open_Citations_Repository';
const OPTIMETA_CITATIONS_OPEN_CITATIONS_TOKEN = 'OptimetaCitations_Open_Citations_Token';

require_once(OPTIMETA_CITATIONS_PLUGIN_PATH . '/vendor/autoload.php');

import('lib.pkp.classes.plugins.GenericPlugin');
import('lib.pkp.classes.site.VersionCheck');
import('lib.pkp.classes.handler.APIHandler');
import('lib.pkp.classes.linkAction.request.AjaxAction');

use Optimeta\Citations\Components\Forms\PublicationForm;
use Optimeta\Citations\Components\Forms\SettingsForm;
use Optimeta\Citations\Dao\CitationsExtendedDAO;
use Optimeta\Citations\Dao\PluginDAO;
use Optimeta\Citations\Deposit\Depositor;
use Optimeta\Citations\Handler\PluginAPIHandler;
use Optimeta\Citations\Model\AuthorModel;
use Optimeta\Citations\Model\WorkModel;

class OptimetaCitationsPlugin extends GenericPlugin
{
    protected $versionSpecificNameState = 'state';

    protected $isEnabledSaved = '0';

    protected $templateParameters = [
        'customScript' => '',
        'pluginStylesheetURL' => '',
        'pluginJavaScriptURL' => '',
        'pluginImagesURL' => '',
        'pluginApiUrl' => '',
        'authorModel' => '',
        'workModel' => '',
        'publicationWork' => '',
        'openAlexURL' => OPTIMETA_CITATIONS_OPENALEX_URL,
        'wikidataURL' => OPTIMETA_CITATIONS_WIKIDATA_URLS['prod'],
        'orcidURL' => 'orcid.org'];

    /**
     * @copydoc Plugin::register
     */
    public function register($category, $path, $mainContextId = null): bool
    {
        // Register the plugin even when it is not enabled
        $success = parent::register($category, $path);

        // get value of isEnabled from database
        $this->isEnabledSaved = $this->getSetting($this->getCurrentContextId(), OPTIMETA_CITATIONS_SAVED_IS_ENABLED);
        // plugin just got enabled
        if (!$this->isEnabledSaved && $this->getEnabled()) {
            // change database value first in case this is called again
            $this->updateSetting($this->getCurrentContextId(), OPTIMETA_CITATIONS_SAVED_IS_ENABLED, '1');

            // plugin was just activated, execute actions
            $this->pluginActivationActions();
        } // plugin just got disabled
        else if ($this->isEnabledSaved && !$this->getEnabled()) {
            // change database value first in case this is called again
            $this->updateSetting($this->getCurrentContextId(), OPTIMETA_CITATIONS_SAVED_IS_ENABLED, '0');

            // plugin just got deactivated, execute actions
            $this->pluginDeactivationActions();
        }

        // Current Request / Context
        $request = $this->getRequest();

        // Fill generic template parameters
        $this->templateParameters['pluginStylesheetURL'] = $request->getBaseUrl() . '/' . $this->getPluginPath() . '/css';
        $this->templateParameters['pluginJavaScriptURL'] = $request->getBaseUrl() . '/' . $this->getPluginPath() . '/js';
        $this->templateParameters['pluginImagesURL'] = $request->getBaseUrl() . '/' . $this->getPluginPath() . '/images';
        $this->templateParameters['pluginApiUrl'] = '';
        $this->templateParameters['authorModel'] = json_encode(get_object_vars(new AuthorModel()));
        $this->templateParameters['workModel'] = json_encode(get_object_vars(new WorkModel()));;

        // Is triggered post install on every install/upgrade.
        HookRegistry::register('Installer::postInstall', array(&$this, 'callbackPostInstall'));

        // Is triggered in Acron Plugin for registering scheduled task
        HookRegistry::register('AcronPlugin::parseCronTab', array($this, 'callbackParseCronTab'));

        if ($success && $this->getEnabled()) {
            $citationsExtendedDAO = new CitationsExtendedDAO();
            DAORegistry::registerDAO('CitationsExtendedDAO', $citationsExtendedDAO);

            // Is triggered with every request from anywhere
            HookRegistry::register('Schema::get::publication', array($this, 'addToSchema'));

            // Is triggered only on these hooks
            HookRegistry::register('Templates::Submission::SubmissionMetadataForm::AdditionalMetadata', array($this, 'submissionWizard'));
            HookRegistry::register('Template::Workflow::Publication', array($this, 'publicationTab'));
            HookRegistry::register('Publication::edit', array($this, 'publicationSave'));

            // Is triggered only on the page defined in Handler method/class
            HookRegistry::register('Dispatcher::dispatch', array($this, 'apiHandler'));
        }

        return $success;
    }

    /**
     * This method is called after the plugin is activated
     * @return void
     */
    public function pluginActivationActions()
    {
        $this->callbackParseCronTabWorkAround();

        // create / alter table required by plugin
        $migrate = new OptimetaCitationsMigration();
        $migrate->createCitationsExtendedIfNotExists();

        error_log('OptimetaCitationsPlugin was enabled');
    }

    /**
     * Workaround for hook AcronPlugin::parseCronTab not working in ojs 3.3.0-x
     * @return void
     */
    public function callbackParseCronTabWorkAround()
    {
        import('plugins.generic.acron.AcronPlugin');
        $acron = new \AcronPlugin();
        $acron->_parseCrontab();
    }

    /**
     * This method is called after the plugin is activated
     * @return void
     */
    public function pluginDeactivationActions()
    {
        error_log('OptimetaCitationsPlugin was disabled');
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
        $pluginDao = new PluginDao;
        $pluginDao->addToSchema($schema);
    }

    /**
     * @param string $hookname
     * @param array $args [string, TemplateManager]
     * @brief Show tab under Publications
     */
    public function publicationTab(string $hookName, array $args): void
    {
        $templateMgr = &$args[1];

        $request = $this->getRequest();
        $context = $request->getContext();
        $submission = $templateMgr->getTemplateVars('submission');
        $submissionId = $submission->getId();

        $dispatcher = $request->getDispatcher();
        $latestPublication = $submission->getLatestPublication();
        $apiBaseUrl = $dispatcher->url($request, ROUTE_API, $context->getData('urlPath'), '');

        $form = new PublicationForm(
            $apiBaseUrl . 'submissions/' . $submissionId . '/publications/' . $latestPublication->getId(),
            $latestPublication,
            __('plugins.generic.optimetaCitationsPlugin.publication.success'));

        $state = $templateMgr->getTemplateVars($this->versionSpecificNameState);
        $state['components'][OPTIMETA_CITATIONS_FORM_NAME] = $form->getConfig();
        $templateMgr->assign($this->versionSpecificNameState, $state);

        $publicationDao = \DAORegistry::getDAO('PublicationDAO');
        $publication = $publicationDao->getById($submissionId);

        $this->templateParameters['pluginApiUrl'] = $apiBaseUrl . OPTIMETA_CITATIONS_API_ENDPOINT;
        $this->templateParameters['submissionId'] = $submissionId;

        $pluginDAO = new PluginDao();
        $this->templateParameters['citationsParsed'] = json_encode($pluginDAO->getCitations($publication));

        $publicationWorkDb = $publication->getData(OPTIMETA_CITATIONS_PUBLICATION_WORK);
        if (!empty($publicationWorkDb) && $publicationWorkDb !== '[]')
            $this->templateParameters['workModel'] = $publicationWorkDb;

        if (OPTIMETA_CITATIONS_IS_TEST_ENVIRONMENT) $this->templateParameters['wikidataURL'] = OPTIMETA_CITATIONS_WIKIDATA_URLS['test'];

        if ($publication->getData('status') === STATUS_PUBLISHED)
            $this->templateParameters['customScript'] .= "window.onload = function(){ document.querySelector('#optimetaCitations button.pkpButton').disabled = true; }" . PHP_EOL;

        $templateMgr->assign($this->templateParameters);

        $templateMgr->display($this->getTemplateResource("submission/form/publicationTab.tpl"));
    }

    /**
     * @param string $hookname
     * @param array $args [
     *   Publication -> new publication
     *   Publication
     *   array parameters/publication properties to be saved
     *   Request
     * @brief process data from post/put
     */
    public function publicationSave(string $hookname, array $args): void
    {
        $publication = $args[0];
        $params = $args[2];
        $request = $this->getRequest();
        $pluginDao = new PluginDao();

        // parsedCitations
        $parsedCitations = $request->getuserVar(OPTIMETA_CITATIONS_FORM_FIELD_PARSED);
        if (array_key_exists(OPTIMETA_CITATIONS_FORM_FIELD_PARSED, $params) &&
            !empty($params[OPTIMETA_CITATIONS_FORM_FIELD_PARSED])) {
            $parsedCitations = $params[OPTIMETA_CITATIONS_FORM_FIELD_PARSED];
        }

        if ((!empty($parsedCitations) && $parsedCitations !== '[]') || $parsedCitations === '[]') {
            $pluginDao->saveCitations($publication, $parsedCitations);
        }

        // publicationWork
        $publicationWork = $request->getuserVar(OPTIMETA_CITATIONS_PUBLICATION_WORK);
        if (array_key_exists(OPTIMETA_CITATIONS_PUBLICATION_WORK, $params) &&
            !empty($params[OPTIMETA_CITATIONS_FORM_FIELD_PARSED])) {
            $publicationWork = $params[OPTIMETA_CITATIONS_PUBLICATION_WORK];
        }

        $publication->setData(OPTIMETA_CITATIONS_PUBLICATION_WORK, $publicationWork);
    }

    /**
     * @param string $hookname
     * @param array $args
     * @return void
     * @brief show citations part on step 3 in submission wizard
     */
    public function submissionWizard(string $hookname, array $args): void
    {
        $templateMgr = &$args[1];

        $request = $this->getRequest();
        $context = $request->getContext();
        $dispatcher = $request->getDispatcher();
        $apiBaseUrl = $dispatcher->url($request, ROUTE_API, $context->getData('urlPath'), '');

        $publicationDao = \DAORegistry::getDAO('PublicationDAO');
        $submissionId = $request->getUserVar('submissionId');
        $publication = $publicationDao->getById($submissionId);

        $this->templateParameters['pluginApiUrl'] = $apiBaseUrl . OPTIMETA_CITATIONS_API_ENDPOINT;
        $this->templateParameters['submissionId'] = $submissionId;

        $pluginDAO = new PluginDao();
        $this->templateParameters['citationsParsed'] = json_encode($pluginDAO->getCitations($publication));

        $publicationWorkDb = $publication->getData(OPTIMETA_CITATIONS_PUBLICATION_WORK);
        if (!empty($publicationWorkDb) && $publicationWorkDb !== '[]')
            $this->templateParameters['workModel'] = $publicationWorkDb;

        if (OPTIMETA_CITATIONS_IS_TEST_ENVIRONMENT) $this->templateParameters['wikidataURL'] = OPTIMETA_CITATIONS_WIKIDATA_URLS['test'];

        $templateMgr->assign($this->templateParameters);

        $templateMgr->display($this->getTemplateResource("submission/form/submissionWizard.tpl"));
    }

    /**
     * @param string $hookName
     * @param PKPRequest $request
     * @return bool
     * @brief execute api handler
     * @throws \Throwable
     */
    public function apiHandler(string $hookName, PKPRequest $request): bool
    {
        $router = $request->getRouter();
        if ($router instanceof \APIRouter && strpos(' ' .
                $request->getRequestPath() . ' ', 'api/v1/' . OPTIMETA_CITATIONS_API_ENDPOINT) !== false) {
            $handler = new PluginAPIHandler($this);
            $router->setHandler($handler);
            $handler->getApp()->run();
            exit;
        }

        return false;
    }

    /**
     * @copydoc Plugin::getActions()
     */
    public function getActions($request, $actionArgs): array
    {
        $actions = parent::getActions($request, $actionArgs);
        if (!$this->getEnabled()) return $actions;

        import('lib.pkp.classes.linkAction.request.AjaxModal');
        $router = $request->getRouter();

        $linkAction[] = new \LinkAction(
            'settings',
            new \AjaxModal(
                $router->url(
                    $request, null, null, 'manage', null,
                    array('verb' => 'settings', 'plugin' => $this->getName(), 'category' => 'generic')),
                $this->getDisplayName()),
            __('manager.plugins.settings'),
            null);

        $linkAction[] = new \LinkAction(
            'test_settings',
            new \AjaxAction(
                $router->url(
                    $request, null, null, 'manage', null,
                    array('verb' => 'test_settings', 'plugin' => $this->getName(), 'category' => 'generic'))),
            __('plugins.generic.optimetaCitations.settings.test.button'),
            null);

        $linkAction[] = new \LinkAction(
            'initialise_plugin',
            new \AjaxAction(
                $router->url(
                    $request, null, null, 'manage', null,
                    array('verb' => 'initialise_plugin', 'plugin' => $this->getName(), 'category' => 'generic'))),
            __('plugins.generic.optimetaCitations.settings.initialise.button'),
            null);

        $linkAction[] = new \LinkAction(
            'batch_deposit',
            new \AjaxAction(
                $router->url(
                    $request, null, null, 'manage', null,
                    array('verb' => 'batch_deposit', 'plugin' => $this->getName(), 'category' => 'generic'))),
            __('plugins.generic.optimetaCitations.settings.deposit.button'),
            null);

        array_unshift($actions, ...$linkAction);

        return $actions;
    }

    /**
     * @copydoc PKPPlugin::getDisplayName
     */
    public function getDisplayName(): string
    {
        return __('plugins.generic.optimetaCitationsPlugin.name');
    }

    /* Plugin required methods */

    /**
     * @copydoc Plugin::manage()
     */
    public function manage($args, $request): \JSONMessage
    {
        $context = $request->getContext();
        switch ($request->getUserVar('verb')) {
            case 'settings':
                // Load the custom form
                $form = new SettingsForm($this);

                // Fetch the form the first time it loads, before the user has tried to save it
                if (!$request->getUserVar('save')) {
                    $form->initData();
                    return new \JSONMessage(true, $form->fetch($request));
                }

                // Validate and save the form data
                $form->readInputData();
                if ($form->validate()) {
                    $form->execute();
                    return new \JSONMessage(true);
                }
            case 'test_settings':
                $notificationManager = new \NotificationManager();
                $user = $request->getUser();
                $notificationManager->createTrivialNotification(
                    $user->getId(),
                    NOTIFICATION_TYPE_WARNING,
                    array('contents' => __('plugins.generic.optimetaCitations.not_implemented')));
                return \DAO::getDataChangedEvent();
            case 'initialise_plugin':
                $this->pluginActivationActions();
                $notificationManager = new \NotificationManager();
                $user = $request->getUser();
                $notificationManager->createTrivialNotification(
                    $user->getId(),
                    NOTIFICATION_TYPE_SUCCESS,
                    array('contents' => __('plugins.generic.optimetaCitations.settings.initialise.notification')));
                return \DAO::getDataChangedEvent();
            case 'batch_deposit':
                $depositor = new Depositor();
                $depositor->batchDeposit();
                $notificationManager = new \NotificationManager();
                $user = $request->getUser();
                $notificationManager->createTrivialNotification(
                    $user->getId(),
                    NOTIFICATION_TYPE_SUCCESS,
                    array('contents' => __('plugins.generic.optimetaCitations.settings.deposit.notification')));
                return \DAO::getDataChangedEvent();
        }
        return parent::manage($args, $request);
    }

    /**
     * @param $hookName string
     * @param $args array [
     * @option array Task files paths
     * @return boolean
     * @see AcronPlugin::parseCronTab()
     */
    public function callbackParseCronTab($hookName, $args): bool
    {
        if ($this->getEnabled() || !\Config::getVar('general', 'installed')) {
            $taskFilesPath =& $args[0]; // Reference needed.
            $taskFilesPath[] = $this->getPluginPath() . DIRECTORY_SEPARATOR . 'scheduledTasks.xml';
        }

        return false;
    }

    /**
     * Post install hook to flag cron tab reload on every install/upgrade.
     * @param $hookName string
     * @param $args array
     * @return boolean
     * @see Installer::postInstall() for the hook call.
     */
    public function callbackPostInstall($hookName, $args)
    {
        error_log('Installer::postInstall() > callbackPostInstall');
        return false;
    }

    /**
     * @copydoc PKPPlugin::getDescription
     */
    public function getDescription(): string
    {
        return __('plugins.generic.optimetaCitationsPlugin.description');
    }

    /**
     * @copydoc Plugin::getInstallMigration()
     */
    public function getInstallMigration()
    {
        return new \Optimeta\Citations\Install\OptimetaCitationsMigration();
    }
}
