<?php
/**
 * @file plugins/generic/citationManager/CitationManagerPlugin.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class CitationManagerPlugin
 * @ingroup plugins_generic_citationmanager
 *
 * @brief Plugin for parsing Citations and submitting to Open Access websites.
 */

// todo CitationManagerPlugin_StructuredCitations is null if no record in database
// todo Depositor Handler
// todo classes/OpenCitations/Deposit.php get rid of $urlIssues
// todo remove CitationsExtended DAO, table, classes
// todo show citation manager tab only if $canAccessPublication && $metadataEnabled

namespace APP\plugins\generic\citationManager;

require_once(__DIR__ . '/vendor/autoload.php');

use APP\core\Application;
use APP\core\Request;
use APP\notification\Notification;
use APP\notification\NotificationManager;
use APP\plugins\generic\citationManager\classes\Components\Forms\SettingsForm;
use APP\plugins\generic\citationManager\classes\DataModels\CitationAuthorModel;
use APP\plugins\generic\citationManager\classes\DataModels\MetadataPublication;
use APP\plugins\generic\citationManager\classes\Db\PluginDAO;
use APP\plugins\generic\citationManager\classes\Db\PluginSchema;
use APP\plugins\generic\citationManager\classes\FrontEnd\ArticlePage;
use APP\plugins\generic\citationManager\classes\Handlers\ApiHandler;
use APP\plugins\generic\citationManager\classes\Handlers\DepositorHandler;
use APP\plugins\generic\citationManager\classes\Handlers\PluginAPIHandler;
use APP\plugins\generic\citationManager\classes\Helpers\ClassHelper;
use APP\plugins\generic\citationManager\classes\PID\Doi;
use APP\plugins\generic\citationManager\classes\PID\GitHubIssue;
use APP\plugins\generic\citationManager\classes\PID\OpenAlex;
use APP\plugins\generic\citationManager\classes\PID\Orcid;
use APP\plugins\generic\citationManager\classes\PID\Wikidata;
use APP\plugins\generic\citationManager\classes\Workflow\SubmissionWizard;
use APP\plugins\generic\citationManager\classes\Workflow\WorkflowTab;
use APP\plugins\generic\citationManager\classes\Workflow\WorkflowSave;
use PKP\config\Config;
use PKP\core\APIRouter;
use PKP\core\JSONMessage;
use PKP\core\PKPApplication;
use PKP\db\DAO;
use PKP\linkAction\LinkAction;
use PKP\linkAction\request\AjaxAction;
use PKP\linkAction\request\AjaxModal;
use PKP\plugins\GenericPlugin;
use PKP\plugins\Hook;
use Throwable;

define('CITATION_MANAGER_PLUGIN_NAME', basename(__FILE__, '.php'));

class CitationManagerPlugin extends GenericPlugin
{
    public const CITATION_MANAGER_FRONTEND_SHOW_STRUCTURED = CITATION_MANAGER_PLUGIN_NAME . '_FrontEndShowStructured';
    public const CITATION_MANAGER_FORM_NAME = CITATION_MANAGER_PLUGIN_NAME . '_PublicationForm';
    public const CITATION_MANAGER_JOURNAL_METADATA = CITATION_MANAGER_PLUGIN_NAME . '_JournalMetadata';
    public const CITATION_MANAGER_PUBLICATION_METADATA = CITATION_MANAGER_PLUGIN_NAME . '_PublicationMetadata';
    public const CITATION_MANAGER_AUTHOR_METADATA = CITATION_MANAGER_PLUGIN_NAME . '_AuthorMetadata';
    public const CITATION_MANAGER_STRUCTURED_CITATIONS = CITATION_MANAGER_PLUGIN_NAME . '_StructuredCitations';

    /* Wikidata */
    public const CITATION_MANAGER_WIKIDATA_USERNAME = CITATION_MANAGER_PLUGIN_NAME . '_Wikidata_Username';
    public const CITATION_MANAGER_WIKIDATA_PASSWORD = CITATION_MANAGER_PLUGIN_NAME . '_Wikidata_Password';

    /* Open Citations */
    public const CITATION_MANAGER_OPEN_CITATIONS_OWNER = CITATION_MANAGER_PLUGIN_NAME . '_OpenCitations_Owner';
    public const CITATION_MANAGER_OPEN_CITATIONS_REPOSITORY = CITATION_MANAGER_PLUGIN_NAME . '_OpenCitations_Repository';
    public const CITATION_MANAGER_OPEN_CITATIONS_TOKEN = CITATION_MANAGER_PLUGIN_NAME . '_OpenCitations_Token';

    /**
     * Is this instance production
     *
     * @var bool
     */
    public bool $isTestMode = false;
    public string $isTestModeKey = 'isTestMode';

    protected array $templateParameters = [
        'assetsUrl' => '',
        'apiBaseUrl' => '',
        'authorModel' => [],
        'publicationMetadata' => [],
        'structuredCitations' => '',
        'doiUrl' => '',
        'openAlexUrl' => '',
        'openCitationsUrl' => '',
        'orcidUrl' => '',
        'wikidataUrl' => ''];

    /**
     * @var PluginDAO
     */
    public PluginDAO $pluginDao;

    /**
     * @copydoc Plugin::register
     */
    public function register($category, $path, $mainContextId = null): bool
    {
        if (parent::register($category, $path, $mainContextId)) {

            Hook::add('Installer::postInstall', [$this, 'updateSchema']);
            Hook::add('AcronPlugin::parseCronTab', array($this, 'callbackParseCronTab'));

            if ($this->getEnabled()) {

                $this->initPlugin($category, $path, $mainContextId);

                $pluginSchema = new PluginSchema($this);
                Hook::add('Schema::get::context', function (string $hookName, array $args) use ($pluginSchema) {
                    $pluginSchema->addToSchemaContext($hookName, $args);
                });
                Hook::add('Schema::get::publication', function (string $hookName, array $args) use ($pluginSchema) {
                    $pluginSchema->addToSchemaPublication($hookName, $args);
                });
                Hook::add('Schema::get::author', function (string $hookName, array $args) use ($pluginSchema) {
                    $pluginSchema->addToSchemaAuthor($hookName, $args);
                });

                $submissionWizard = new SubmissionWizard($this);
                Hook::add('Template::SubmissionWizard::Section', function (string $hookName, array $args) use ($submissionWizard) {
                    $submissionWizard->execute($hookName, $args, $this->templateParameters);
                });

                $workflowTab = new WorkflowTab($this);
                Hook::add('Template::Workflow', function (string $hookName, array $args) use ($workflowTab) {
                    $workflowTab->execute($hookName, $args, $this->templateParameters);
                });

                $workflowSave = new WorkflowSave($this);
                Hook::add('Publication::edit', function (string $hookName, array $args) use ($workflowSave) {
                    $workflowSave->execute($hookName, $args);
                });

                $apiHandler = new ApiHandler($this);
                Hook::add('Dispatcher::dispatch', function (string $hookName, array $args) use ($apiHandler) {
                    $apiHandler->execute($hookName, $args);
                });

                $articlePage = new ArticlePage($this);
                Hook::add('TemplateManager::display', function (string $hookName, array $args) use ($articlePage) {
                    $articlePage->execute($hookName, $args, $this->templateParameters);
                });
            }

            return true;
        }

        return false;
    }

    /**
     * Initializes this plugin
     *
     * @param $category
     * @param $path
     * @param $mainContextId
     *
     * @return void
     */
    public function initPlugin($category, $path, $mainContextId = null): void
    {
        $request = $this->getRequest();
        $context = $request->getContext();

        $isTestMode = Config::getVar(CITATION_MANAGER_PLUGIN_NAME, $this->isTestModeKey);
        if (!empty($isTestMode) && (strtolower($isTestMode) === 'true' || (string)$isTestMode === '1'))
            $this->isTestMode = true;

        $pidOpenAlex = new OpenAlex();
        $pidGitHubIssue = new GitHubIssue();
        $pidOrcid = new Orcid();
        $pidWikidata = new Wikidata();
        $pidDoi = new Doi();

        $apiBaseUrl = '';
        if (!empty($context) && !empty($context->getData('urlPath'))) {
            $apiBaseUrl = $request->getDispatcher()->url(
                $request,
                PKPApplication::ROUTE_API,
                $context->getData('urlPath'),
                '');
        }

        $this->templateParameters = [
            'assetsUrl' => $request->getBaseUrl() . '/' . $this->getPluginPath() . '/assets',
            'apiBaseUrl' => $apiBaseUrl,
            'authorModel' => json_encode(ClassHelper::getClassAsArrayNullAssigned(new CitationAuthorModel())),
            'publicationMetadata' => json_encode(ClassHelper::getClassAsArrayNullAssigned(new MetadataPublication())),
            'structuredCitations' => '',
            'doiUrl' => $pidDoi->getPrefix($this->isTestMode),
            'openAlexUrl' => $pidOpenAlex->getPrefix($this->isTestMode),
            'openCitationsUrl' => $pidGitHubIssue->getPrefix($this->isTestMode),
            'orcidUrl' => $pidOrcid->getPrefix($this->isTestMode),
            'wikidataUrl' => $pidWikidata->getPrefix($this->isTestMode)
        ];

        $this->pluginDao = new PluginDAO($this);
    }

//    /**
//     * Hook callback: register output filter to replace raw with structured citations.
//     *
//     * @see TemplateManager::display()
//     */
//    public function frontEndTemplate($hookName, $args): bool
//    {
//        /* @var TemplateManager $templateMgr */
//        $templateMgr = $args[0];
//        $template = $args[1];
//        $request = PKPApplication::get()->getRequest();
//
//        switch ($template) {
//            case 'frontend/pages/article.tpl':
//                if ($this->getSetting(
//                        $this->getCurrentContextId(),
//                        CitationManagerPlugin::CITATION_MANAGER_FRONTEND_SHOW_STRUCTURED) === 'true') {
//                    $templateMgr->addStyleSheet(
//                        'citationManager',
//                        $this->templateParameters['assetsUrl'] . '/css/style.css',
//                        array('contexts' => array('frontend'))
//                    );
//
//                    try {
//                        $templateMgr->registerFilter("output", array($this, 'frontEndRegisterFilter'));
//                    } catch (SmartyException $e) {
//                        error_log(__METHOD__ . ' ' . $e->getMessage());
//                    }
//                }
//                break;
//            default:
//                break;
//        }
//
//        return false;
//    }
//
//    /**
//     * Output filter to replace raw with structured citations.
//     *
//     * @param $output
//     * @param $templateMgr
//     *
//     * @return string
//     */
//    public function frontEndRegisterFilter($output, $templateMgr): string
//    {
//        $request = Application::get()->getRequest();
//        $context = $request->getContext();
//
//        /* @var Publication $publication */
//        $publication = $templateMgr->getTemplateVars('currentPublication');
//
//        $article = new Article($this);
//        $references = $article->getCitationsAsHtml($publication->getId());
//
//        $id = 'id-7dd2e9191a58485da3510d8d9ac6ae88';
//        $newOutput =
//            "<div id='$id' style='display: none;'>$references</div>
//            <script>
//                window.onload = function(){
//                    let src = document.querySelector('#$id');
//                    let dst = document.querySelector('.main_entry .references .value');
//                    dst.innerHTML = src.innerHTML;
//                }
//            </script>";
//
//        if ($context != null) {
//            $output .= $newOutput;
//            $templateMgr->unregisterFilter("output", array($this, 'frontEndRegisterFilter'));
//        }
//
//        return $output;
//    }
//
//    /**
//     * Show tab under Publications
//     *
//     * @param string $hookName
//     * @param array $args [string, TemplateManager]
//     * @return void
//     *
//     * @throws Exception
//     */
//    public function publicationTab(string $hookName, array $args): void
//    {
//        $templateMgr = &$args[1];
//
//        $request = $this->getRequest();
//        $context = $request->getContext();
//        $submission = $templateMgr->getTemplateVars('submission');
//        $submissionId = $submission->getId();
//        $publication = $submission->getLatestPublication();
//        $publicationId = $publication->getId();
//
//        $apiBaseUrl = $request->getDispatcher()->url(
//            $request,
//            PKPApplication::ROUTE_API,
//            $context->getData('urlPath'),
//            '');
//
//        $locales = $context->getSupportedLocaleNames();
//        $locales = array_map(
//            fn(string $locale, string $name) => ['key' => $locale, 'label' => $name],
//            array_keys($locales), $locales);
//
//        $form = new PublicationForm(
//            CitationManagerPlugin::CITATION_MANAGER_FORM_NAME,
//            'PUT',
//            $apiBaseUrl . 'submissions/' . $submissionId . '/publications/' . $publicationId,
//            $locales,
//            $publication,
//            __('plugins.generic.citationManager.publication.success'),
//            $this);
//
//        $state = $templateMgr->getTemplateVars('state');
//        $state['components'][CitationManagerPlugin::CITATION_MANAGER_FORM_NAME] = $form->getConfig();
//        $templateMgr->assign('state', $state);
//
//        $this->templateParameters['publicationMetadata'] = json_encode($this->pluginDao->getPublicationMetadata($publicationId));
//        $this->templateParameters['structuredCitations'] = json_encode($this->pluginDao->getCitations($publicationId));
//
//        $templateMgr->assign($this->templateParameters);
//
//        $templateMgr->display($this->getTemplateResource("submission/form/publicationTab.tpl"));
//    }
//
//    /**
//     * Process data from post/put
//     *
//     * @param string $hookname
//     * @param array $args [ Publication, parameters/publication, Request ]
//     */
//    public function publicationSave(string $hookname, array $args): void
//    {
//        $publication = $args[0];
//        $params = $args[2];
//        $request = $this->getRequest();
//
//        // structuredCitations
//
//        // submissionWizard
//        $structuredCitations = $request->getuserVar(CitationManagerPlugin::CITATION_MANAGER_STRUCTURED_CITATIONS);
//
//        // publicationTab
//        if (array_key_exists(CitationManagerPlugin::CITATION_MANAGER_STRUCTURED_CITATIONS, $params)) {
//            if (!empty($params[CitationManagerPlugin::CITATION_MANAGER_STRUCTURED_CITATIONS])) {
//                $structuredCitations = $params[CitationManagerPlugin::CITATION_MANAGER_STRUCTURED_CITATIONS];
//            }
//        }
//        $publication->setData(CitationManagerPlugin::CITATION_MANAGER_STRUCTURED_CITATIONS, $structuredCitations);
//
//        // publicationMetadata
//        // submissionWizard
//        $publicationMetadata = $request->getuserVar(CitationManagerPlugin::CITATION_MANAGER_PUBLICATION_METADATA);
//        // publicationTab
//        if (array_key_exists(CitationManagerPlugin::CITATION_MANAGER_PUBLICATION_METADATA, $params)) {
//            if (!empty($params[CitationManagerPlugin::CITATION_MANAGER_STRUCTURED_CITATIONS])) {
//                $publicationMetadata = $params[CitationManagerPlugin::CITATION_MANAGER_PUBLICATION_METADATA];
//            }
//        }
//        $publication->setData(CitationManagerPlugin::CITATION_MANAGER_PUBLICATION_METADATA, $publicationMetadata);
//    }
//
//    /**
//     * Show citations part on step 3 in submission wizard
//     *
//     * @param string $hookname
//     * @param array $args
//     *
//     * @return void
//     */
//    public function submissionWizard(string $hookname, array $args): void
//    {
//        $templateMgr = &$args[1];
//
//        $submission = $templateMgr->getTemplateVars('submission');
//        $publication = $submission->getCurrentPublication();
//
//        $this->templateParameters['publicationMetadata'] = json_encode($this->pluginDao->getPublicationMetadata($publication->getId()));
//        $this->templateParameters['structuredCitations'] = json_encode($this->pluginDao->getCitations($publication->getId()));
//
//        $templateMgr->assign($this->templateParameters);
//
//        $templateMgr->display($this->getTemplateResource("submission/form/submissionWizard.tpl"));
//    }
//
//    /**
//     * Execute API Handler
//     *
//     * @param string $hookName
//     * @param array $args
//     *
//     * @return bool
//     */
//    public function apiHandler(string $hookName, array $args): bool
//    {
//        try {
//            /* @var Request $request */
//            $request = $args[0];
//
//            $router = $request->getRouter();
//            if ($router instanceof APIRouter
//                && str_contains(
//                    $request->getRequestPath(),
//                    'api/v1/' . CITATION_MANAGER_PLUGIN_NAME)
//            ) {
//                $handler = new PluginAPIHandler($this);
//                $router->setHandler($handler);
//                $handler->getApp()->run();
//                exit;
//            }
//        } catch (Throwable $ex) {
//            error_log(__METHOD__ . ' ' . $ex->getMessage());
//        }
//
//        return false;
//    }

    /**
     * @copydoc Plugin::getActions()
     */
    public function getActions($request, $actionArgs): array
    {
        $actions = parent::getActions($request, $actionArgs);

        if (!$this->getEnabled()) return $actions;

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
            __('plugins.generic.citationManager.settings.test.button'),
            null);

        $linkAction[] = new LinkAction(
            'batch_deposit',
            new AjaxAction(
                $router->url(
                    $request, null, null, 'manage', null,
                    array('verb' => 'batch_deposit', 'plugin' => $this->getName(), 'category' => 'generic'))),
            __('plugins.generic.citationManager.settings.deposit.button'),
            null);

        array_unshift($actions, ...$linkAction);

        return $actions;
    }

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
                if ($form->validate()) $form->execute();
                return new JSONMessage(true);
            case 'test_settings':
                $notificationManager = new NotificationManager();
                $notificationManager->createTrivialNotification(
                    Application::get()->getRequest()->getUser()->getId(),
                    Notification::NOTIFICATION_TYPE_WARNING,
                    array('contents' => __('plugins.generic.citationManager.not_implemented')));
                return DAO::getDataChangedEvent();
            case 'batch_deposit':
                $depositorHandler = new DepositorHandler($this);
                $depositorHandler->batchDeposit();
                $notificationManager = new NotificationManager();
                $notificationManager->createTrivialNotification(
                    Application::get()->getRequest()->getUser()->getId(),
                    Notification::NOTIFICATION_TYPE_SUCCESS,
                    array('contents' => __('plugins.generic.citationManager.settings.deposit.notification')));
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
    public function callbackParseCronTab(string $hookName, array $args): bool
    {
        if ($this->getEnabled() || !Config::getVar('general', 'installed')) {

            $taskFilesPath =& $args[0];

            $taskFilesPath[] = $this->getPluginPath() . DIRECTORY_SEPARATOR . 'scheduledTasks.xml';
        }

        return false;
    }

    /**
     * @copydoc Plugin::updateSchema()
     */
    public function updateSchema($hookName, $args): void
    {
        // there is nothing to update for now
    }

    /**
     * @copydoc PKPPlugin::getDescription
     */
    public function getDescription(): string
    {
        return __('plugins.generic.citationManager.description');
    }

    /**
     * @copydoc PKPPlugin::getDisplayName
     */
    public function getDisplayName(): string
    {
        return __('plugins.generic.citationManager.displayName');
    }

    /**
     * @copydoc Plugin::isSitePlugin
     */
    public function isSitePlugin(): bool
    {
        return true;
    }
}

// For backwards compatibility -- expect this to be removed approx. OJS/OMP/OPS 3.6
if (!PKP_STRICT_MODE) {
    class_alias('\APP\plugins\generic\citationManager\CitationManagerPlugin', '\CitationManagerPlugin');
}
