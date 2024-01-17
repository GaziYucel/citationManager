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

require_once(OPTIMETA_CITATIONS_PLUGIN_PATH . '/vendor/autoload.php');

use APP\core\Application;
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
use APP\template\TemplateManager;
use PKP\core\JSONMessage;
use PKP\core\PKPApplication;
use PKP\db\DAORegistry;
use PKP\linkAction\LinkAction;
use PKP\linkAction\request\AjaxAction;
use PKP\linkAction\request\AjaxModal;
use PKP\plugins\GenericPlugin;
use PKP\submission\PKPSubmission;

class OptimetaCitationsPlugin extends GenericPlugin
{
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
    public const OPTIMETA_CITATIONS_OPENALEX_URL = 'https://openalex.org';
    public const OPTIMETA_CITATIONS_OPENALEX_API_URL = 'https://api.openalex.org/';
    public const OPTIMETA_CITATIONS_WIKIDATA_URL = 'https://www.wikidata.org/wiki';
    public const OPTIMETA_CITATIONS_WIKIDATA_API_URL = 'https://www.wikidata.org/w/api.php';
    public const OPTIMETA_CITATIONS_WIKIDATA_URL_TEST = 'https://test.wikidata.org/wiki';
    public const OPTIMETA_CITATIONS_WIKIDATA_API_URL_TEST = 'https://test.wikidata.org/w/api.php';
    public const OPTIMETA_CITATIONS_ORCID_URL = 'https://orcid.org';
    public const OPTIMETA_CITATIONS_ORCID_API_URL = 'https://pub.orcid.org/v2.1';
    public const OPTIMETA_CITATIONS_OPEN_ALEX_URL = 'https://openalex.org';
    public const OPTIMETA_CITATIONS_DOI_URL = 'https://doi.org';

    /**
     * Is this instance production
     * @var bool
     */
    public bool $isProduction = false;

    public string $versionSpecificNameState = 'state'; //todo: can be replaced

    public string $isEnabledSaved = '0';

    public array $templateParameters = [
        'customScript' => '',
        'pluginStylesheetURL' => '',
        'pluginJavaScriptURL' => '',
        'pluginImagesURL' => '',
        'pluginApiUrl' => '',
        'isPublished' => 'false',
        'authorModel' => '',
        'workModel' => '',
        'publicationWork' => '',
        'statusCodePublished' => 3,
        'openAlexURL' => OPTIMETA_CITATIONS_OPENALEX_URL,
        'wikidataURL' => OPTIMETA_CITATIONS_WIKIDATA_URL,
        'orcidURL' => OPTIMETA_CITATIONS_ORCID_URL];

    /**
     * @var PluginDAO
     */
    public PluginDAO $pluginDao;

    /**
     * @copydoc Plugin::register
     */
    public function register($category, $path, $mainContextId = null): bool
    {
        // Register the plugin even when it is not enabled
        $success = parent::register($category, $path);

//        if ($this->getSetting($this->getCurrentContextId(), OPTIMETA_CITATIONS_IS_PRODUCTION_KEY) === 'true') {
//            $this->isProduction = true;
//        }

//         get value of isEnabled from database
//        $this->isEnabledSaved = $this->getSetting($this->getCurrentContextId(), OPTIMETA_CITATIONS_SAVED_IS_ENABLED);
        // plugin just got enabled
//        if (!$this->isEnabledSaved && $this->getEnabled()) {
            // change database value first in case this is called again
//            $this->updateSetting($this->getCurrentContextId(), OPTIMETA_CITATIONS_SAVED_IS_ENABLED, '1');

            // plugin was just activated, execute actions
//            $this->pluginActivationActions();
//        }
        // plugin just got disabled
//        else if ($this->isEnabledSaved && !$this->getEnabled()) {
            // change database value first in case this is called again
//            $this->updateSetting($this->getCurrentContextId(), OPTIMETA_CITATIONS_SAVED_IS_ENABLED, '0');

            // plugin just got deactivated, execute actions
//            $this->pluginDeactivationActions();
//        }

        // Is triggered post install on every install/upgrade.
//        Hook::add('Installer::postInstall', array(&$this, 'callbackPostInstall'));

        // Is triggered in Acron Plugin for registering scheduled task
//        Hook::add('AcronPlugin::parseCronTab', array($this, 'callbackParseCronTab'));

        if ($success && $this->getEnabled()) {
            $this->pluginDao = new PluginDAO($this);

            $citationsExtendedDAO = new CitationsExtendedDAO();
            DAORegistry::registerDAO('CitationsExtendedDAO', $citationsExtendedDAO);

            // Current Request / Context
            $request = $this->getRequest();

            // Fill generic template parameters
            $this->templateParameters['pluginStylesheetURL'] = $request->getBaseUrl() . '/' . $this->getPluginPath() . '/css';
            $this->templateParameters['pluginJavaScriptURL'] = $request->getBaseUrl() . '/' . $this->getPluginPath() . '/js';
            $this->templateParameters['pluginImagesURL'] = $request->getBaseUrl() . '/' . $this->getPluginPath() . '/images';
            $this->templateParameters['pluginApiUrl'] = '';
            $this->templateParameters['authorModel'] = json_encode(get_object_vars(new AuthorModel()));
            $this->templateParameters['workModel'] = json_encode(get_object_vars(new WorkModel()));

            // Is triggered with every request from anywhere
//            Hook::add('Schema::get::publication', array($this, 'addToSchema'));

            // Is triggered only on these hooks
//            Hook::add('Templates::Submission::SubmissionMetadataForm::AdditionalMetadata', array($this, 'submissionWizard'));
//            Hook::add('Template::Workflow::Publication', array($this, 'publicationTab'));
//            Hook::add('Publication::edit', array($this, 'publicationSave'));

            // Is triggered only on the page defined in Handler method/class
//            Hook::add('Dispatcher::dispatch', array($this, 'apiHandler'));

            // Register callback to add text to registration page
//            Hook::add('TemplateManager::display', array($this, 'handleTemplateDisplay'));

            error_log('success: '. $success . ' | ' . '$this->getEnabled($mainContextId): ' . $this->getEnabled($mainContextId));
        }

        return $success;
    }

    /**
     * Hook callback: register output filter to replace raw with structured citations.
     * @see TemplateManager::display()
     */
    public function handleTemplateDisplay($hookName, $args): bool
    {
        $templateMgr = $args[0];
        $template = $args[1];
        $request = PKPApplication::get()->getRequest();

        switch($template){
            case 'frontend/pages/article.tpl':
                if ($this->getSetting($this->getCurrentContextId(), OPTIMETA_CITATIONS_FRONTEND_SHOW_STRUCTURED) === 'true') {
                    $templateMgr->addStyleSheet(
                        'optimetaCitations',
                        $request->getBaseUrl() . '/' . $this->getPluginPath() . '/css/optimetaCitations.css',
                        array('contexts' => array('frontend'))
                    );

                    $templateMgr->registerFilter("output", array($this, 'registrationFilter'));
                }
                break;
            default:
                break;
        }

        return false;
    }

    /**
     * Output filter to replace raw with structured citations.
     * @param $output string
     * @param $templateMgr TemplateManager
     * @return string
     */
    public function registrationFilter($output, $templateMgr): string
    {
        $request = Application::get()->getRequest();
        $context = $request->getContext();

        $publication = $templateMgr->getTemplateVars('currentPublication');

        $article = new Article();
        $references = $article->getCitationsAsHtml($publication);
        if (!$this->isProduction)
            $references = str_replace(OPTIMETA_CITATIONS_WIKIDATA_URL, OPTIMETA_CITATIONS_WIKIDATA_URL_TEST, $references);

        $newOutput =
            "<div id='optimetaCitations_StructuredCitations_1234567890' style='display: none;'>$references</div>" . PHP_EOL .
            "<script> 
                window.onload = function(){
                    let src = document.querySelector('#optimetaCitations_StructuredCitations_1234567890');
                    let dst = document.querySelector('.main_entry .references .value'); 
                    dst.innerHTML = src.innerHTML;
                }
            </script>";

        if ($context != null) {
            $output .= $newOutput;
            $templateMgr->unregisterFilter("output", array($this, 'registrationFilter'));
        }

        return $output;
    }

    /**
     * This method is called after the plugin is activated
     * @return void
     */
    public function pluginActivationActions(): void
    {
//        $this->callbackParseCronTabWorkAround();

        // create / alter table required by plugin
        $migrate = new OptimetaCitationsMigration();
        $migrate->createCitationsExtendedIfNotExists();

        error_log('OptimetaCitationsPlugin was enabled');
    }

    /**
     * Workaround for hook AcronPlugin::parseCronTab not working in ojs 3.3.0-x
     * @return void
     */
    public function callbackParseCronTabWorkAround(): void
    {
//        import('plugins.generic.acron.AcronPlugin');
        $acron = new AcronPlugin();
//        $acron->_parseCrontab();
    }

    /**
     * This method is called after the plugin is activated
     * @return void
     */
    public function pluginDeactivationActions(): void
    {
        error_log('OptimetaCitationsPlugin was disabled');
    }

    /**
     * Add a property to the publication schema
     * @param $hookName string `Schema::get::publication`
     * @param $args [[
     * @option object Publication schema
     */
    public function addToSchema(string $hookName, array $args): void
    {
        $schema = $args[0];
        $this->pluginDao->addToSchema($schema);
    }

    /**
     * Show tab under Publications
     * @param string $hookName
     * @param array $args [string, TemplateManager]
     * @return void
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
        $apiBaseUrl = $dispatcher->url($request, PKPApplication::ROUTE_API, $context->getData('urlPath'), '');

        $locales = $context->getSupportedLocaleNames();
        $locales = array_map(fn (string $locale, string $name) => ['key' => $locale, 'label' => $name], array_keys($locales), $locales);

        $form = new PublicationForm(
            OPTIMETA_CITATIONS_FORM_NAME,
            'PUT',
            $apiBaseUrl . 'submissions/' . $submissionId . '/publications/' . $latestPublication->getId(),
            $locales,
            $latestPublication,
            __('plugins.generic.optimetaCitations.publication.success'),
            $this);

        $state = $templateMgr->getTemplateVars($this->versionSpecificNameState);
        $state['components'][OPTIMETA_CITATIONS_FORM_NAME] = $form->getConfig();
        $templateMgr->assign($this->versionSpecificNameState, $state);

        $publicationDao = DAORegistry::getDAO('PublicationDAO');
        $publication = $publicationDao->getById($submissionId);

        $this->templateParameters['pluginApiUrl'] = $apiBaseUrl . OPTIMETA_CITATIONS_API_ENDPOINT;
        $this->templateParameters['submissionId'] = $submissionId;
        $this->templateParameters['doiBaseUrl'] = OPTIMETA_CITATIONS_DOI_URL;

        $this->templateParameters['citationsParsed'] = json_encode($this->pluginDao->getCitations($publication));

        $publicationWorkDb = $publication->getData(OPTIMETA_CITATIONS_PUBLICATION_WORK);
        if (!empty($publicationWorkDb) && $publicationWorkDb !== '[]')
            $this->templateParameters['workModel'] = $publicationWorkDb;

        if (!$this->isProduction)
            $this->templateParameters['wikidataURL'] = OPTIMETA_CITATIONS_WIKIDATA_URL_TEST;

        $this->templateParameters['statusCodePublished'] = PKPSubmission::STATUS_PUBLISHED;

        $templateMgr->assign($this->templateParameters);

        $templateMgr->display($this->getTemplateResource("submission/form/publicationTab.tpl"));
    }

    /**
     * Process data from post/put
     * @param string $hookname
     * @param array $args [
     *   Publication -> new publication
     *   Publication
     *   array parameters/publication properties to be saved
     *   Request
     */
    public function publicationSave(string $hookname, array $args): void
    {
        $publication = $args[0];
        $params = $args[2];
        $request = $this->getRequest();

        // parsedCitations
        $parsedCitations = $request->getuserVar(OPTIMETA_CITATIONS_FORM_FIELD_PARSED);
        if (array_key_exists(OPTIMETA_CITATIONS_FORM_FIELD_PARSED, $params) &&
            !empty($params[OPTIMETA_CITATIONS_FORM_FIELD_PARSED])) {
            $parsedCitations = $params[OPTIMETA_CITATIONS_FORM_FIELD_PARSED];
        }

        if ((!empty($parsedCitations) && $parsedCitations !== '[]') || $parsedCitations === '[]') {
            $this->pluginDao->saveCitations($publication, $parsedCitations);
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
     * Show citations part on step 3 in submission wizard
     * @param string $hookname
     * @param array $args
     * @return void
     */
    public function submissionWizard(string $hookname, array $args): void
    {
        $templateMgr = &$args[1];

        $request = $this->getRequest();
        $context = $request->getContext();
        $dispatcher = $request->getDispatcher();
        $apiBaseUrl = $dispatcher->url($request, Application::ROUTE_API, $context->getData('urlPath'), '');

        $publicationDao = DAORegistry::getDAO('PublicationDAO');
        $submissionId = $request->getUserVar('submissionId');
        $publication = $publicationDao->getById($submissionId);

        $this->templateParameters['pluginApiUrl'] = $apiBaseUrl . OPTIMETA_CITATIONS_API_ENDPOINT;
        $this->templateParameters['submissionId'] = $submissionId;
        $this->templateParameters['doiBaseUrl'] = OPTIMETA_CITATIONS_DOI_URL;

        $this->templateParameters['citationsParsed'] = json_encode($this->pluginDao->getCitations($publication));

        $publicationWorkDb = $publication->getData(OPTIMETA_CITATIONS_PUBLICATION_WORK);
        if (!empty($publicationWorkDb) && $publicationWorkDb !== '[]')
            $this->templateParameters['workModel'] = $publicationWorkDb;

        if (!$this->isProduction)
            $this->templateParameters['wikidataURL'] = OPTIMETA_CITATIONS_WIKIDATA_URL_TEST;

        $this->templateParameters['statusCodePublished'] = PKPSubmission::STATUS_PUBLISHED;

        $templateMgr->assign($this->templateParameters);

        $templateMgr->display($this->getTemplateResource("submission/form/submissionWizard.tpl"));
    }

    /**
     * Execute API Handler
     * @param string $hookName
     * @param PKPRequest $request
     * @return bool
     */
    public function apiHandler(string $hookName, PKPRequest $request): bool
    {
        try {
            $router = $request->getRouter();
            if ($router instanceof APIRouter && strpos(' ' .
                    $request->getRequestPath() . ' ', 'api/v1/' . OPTIMETA_CITATIONS_API_ENDPOINT) !== false) {
                $handler = new PluginAPIHandler($this);
                $router->setHandler($handler);
                $handler->getApp()->run();
                exit;
            }
        } catch (Throwable $e) {
        }

        return false;
    }

    /**
     * @copydoc Plugin::getActions()
     */
    public function getActions($request, $actionArgs): array
    {
        $actions = parent::getActions($request, $actionArgs);

        if (!$this->getEnabled()) {
            return $actions;
        }

//        import('lib.pkp.classes.linkAction.request.AjaxModal');
        $router = $request->getRouter();

        $linkAction[] = new LinkAction(
            'settings',
            new AjaxModal(
                $router->url(
                    $request, null, null, 'manage', null,
                    array('verb' => 'settings', 'plugin' => $this->getName(), 'category' => 'generic')),
                $this->getDisplayName()),
            __('manager.plugins.settings'),
            null);

        $linkAction[] = new LinkAction(
            'test_settings',
            new AjaxAction(
                $router->url(
                    $request, null, null, 'manage', null,
                    array('verb' => 'test_settings', 'plugin' => $this->getName(), 'category' => 'generic'))),
            __('plugins.generic.optimetaCitations.settings.test.button'),
            null);

        $linkAction[] = new LinkAction(
            'initialise_plugin',
            new AjaxAction(
                $router->url(
                    $request, null, null, 'manage', null,
                    array('verb' => 'initialise_plugin', 'plugin' => $this->getName(), 'category' => 'generic'))),
            __('plugins.generic.optimetaCitations.settings.initialise.button'),
            null);

        $linkAction[] = new LinkAction(
            'batch_deposit',
            new AjaxAction(
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
        return __('plugins.generic.optimetaCitations.name');
    }

    /* Plugin required methods */

    /**
     * @copydoc Plugin::manage()
     */
    public function manage($args, $request): JSONMessage
    {
        $context = $request->getContext();
        switch ($request->getUserVar('verb')) {
            case 'settings':
                // Load the custom form
                $form = new SettingsForm($this);

                // Fetch the form the first time it loads, before the user has tried to save it
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
            case 'test_settings':
                $notificationManager = new NotificationManager();
                $user = $request->getUser();
                $notificationManager->createTrivialNotification(
                    $user->getId(),
                    Notification::NOTIFICATION_TYPE_WARNING,
                    array('contents' => __('plugins.generic.optimetaCitations.not_implemented')));
                return \DAO::getDataChangedEvent();
            case 'initialise_plugin':
//                $this->pluginActivationActions();
                $migrate = new OptimetaCitationsMigration();
                $migrate->createCitationsExtendedIfNotExists();
                $notificationManager = new NotificationManager();
                $user = $request->getUser();
                $notificationManager->createTrivialNotification(
                    $user->getId(),
                    Notification::NOTIFICATION_TYPE_SUCCESS,
                    array('contents' => __('plugins.generic.optimetaCitations.settings.initialise.notification')));
                return DAO::getDataChangedEvent();
            case 'batch_deposit':
                $depositor = new DepositorHandler();
                $depositor->batchDeposit();
                $notificationManager = new NotificationManager();
                $user = $request->getUser();
                $notificationManager->createTrivialNotification(
                    $user->getId(),
                    Notification::NOTIFICATION_TYPE_SUCCESS,
                    array('contents' => __('plugins.generic.optimetaCitations.settings.deposit.notification')));
                return DAO::getDataChangedEvent();
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
    public function callbackPostInstall($hookName, $args): bool
    {
        error_log('Installer::postInstall() > callbackPostInstall');
        return false;
    }

    /**
     * @copydoc PKPPlugin::getDescription
     */
    public function getDescription(): string
    {
        return __('plugins.generic.optimetaCitations.description');
    }

    /**
     * @copydoc Plugin::getInstallMigration()
     */
    public function getInstallMigration(): OptimetaCitationsMigration
    {
        return new OptimetaCitationsMigration();
    }
}

// For backwards compatibility -- expect this to be removed approx. OJS/OMP/OPS 3.6
if (!PKP_STRICT_MODE) {
    class_alias('\APP\plugins\generic\optimetaCitations\OptimetaCitationsPlugin', '\OptimetaCitationsPlugin');
}
