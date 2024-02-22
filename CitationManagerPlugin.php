<?php
/**
 * @file CitationManagerPlugin.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class CitationManagerPlugin
 * @brief Plugin for parsing Citations and submitting to Open Access websites.
 */

// todo: show citation manager tab only if $canAccessPublication && $metadataEnabled

require_once(__DIR__ . '/vendor/autoload.php');

import('lib.pkp.classes.plugins.GenericPlugin');
import('lib.pkp.classes.site.VersionCheck');
import('lib.pkp.classes.handler.APIHandler');
import('lib.pkp.classes.linkAction.request.AjaxAction');

use APP\plugins\generic\citationManager\classes\DataModels\Citation\AuthorModel;
use APP\plugins\generic\citationManager\classes\DataModels\Metadata\PublicationMetadata;
use APP\plugins\generic\citationManager\classes\Db\PluginSchema;
use APP\plugins\generic\citationManager\classes\FrontEnd\ArticleView;
use APP\plugins\generic\citationManager\classes\Handlers\PluginAPIHandler;
use APP\plugins\generic\citationManager\classes\Helpers\ClassHelper;
use APP\plugins\generic\citationManager\classes\Helpers\LogHelper;
use APP\plugins\generic\citationManager\classes\PID\Doi;
use APP\plugins\generic\citationManager\classes\PID\GitHubIssue;
use APP\plugins\generic\citationManager\classes\PID\OpenAlex;
use APP\plugins\generic\citationManager\classes\PID\Orcid;
use APP\plugins\generic\citationManager\classes\PID\Wikidata;
use APP\plugins\generic\citationManager\classes\Settings\Actions;
use APP\plugins\generic\citationManager\classes\Settings\Manage;
use APP\plugins\generic\citationManager\classes\Workflow\SubmissionWizard;
use APP\plugins\generic\citationManager\classes\Workflow\WorkflowSave;
use APP\plugins\generic\citationManager\classes\Workflow\WorkflowTab;

define('CITATION_MANAGER_PLUGIN_NAME', basename(__FILE__, '.php'));

class CitationManagerPlugin extends GenericPlugin
{
    /** @var string Whether show the structured or the raw citations */
    public const CITATION_MANAGER_FRONTEND_SHOW_STRUCTURED = CITATION_MANAGER_PLUGIN_NAME . '_FrontEndShowStructured';
    /** @var string Key for the journal metadata saved in journal */
    public const CITATION_MANAGER_METADATA_JOURNAL = CITATION_MANAGER_PLUGIN_NAME . '_MetadataJournal';
    /** @var string Key for the publication metadata saved in publication */
    public const CITATION_MANAGER_METADATA_PUBLICATION = CITATION_MANAGER_PLUGIN_NAME . '_MetadataPublication';
    /** @var string Key for the author metadata saved in author */
    public const CITATION_MANAGER_METADATA_AUTHOR = CITATION_MANAGER_PLUGIN_NAME . '_MetadataAuthor';
    /** @var string Key for structured citations saved in publications */
    public const CITATION_MANAGER_CITATIONS_STRUCTURED = CITATION_MANAGER_PLUGIN_NAME . '_CitationsStructured';
    /** @var string Key used for the form used in workflow and submission wizard */
    public const CITATION_MANAGER_STRUCTURED_CITATIONS_FORM = CITATION_MANAGER_PLUGIN_NAME . '_StructuredCitationsForm';
    /** @var string Wikidata username */
    public const CITATION_MANAGER_WIKIDATA_USERNAME = CITATION_MANAGER_PLUGIN_NAME . '_Wikidata_Username';
    /** @var string Wikidata password */
    public const CITATION_MANAGER_WIKIDATA_PASSWORD = CITATION_MANAGER_PLUGIN_NAME . '_Wikidata_Password';
    /** @var string GitHub handle / account used for Open Citations */
    public const CITATION_MANAGER_OPEN_CITATIONS_OWNER = CITATION_MANAGER_PLUGIN_NAME . '_OpenCitations_Owner';
    /** @var string GitHub repository used for Open Citations */
    public const CITATION_MANAGER_OPEN_CITATIONS_REPOSITORY = CITATION_MANAGER_PLUGIN_NAME . '_OpenCitations_Repository';
    /** @var string GitHub APi token used for Open Citations */
    public const CITATION_MANAGER_OPEN_CITATIONS_TOKEN = CITATION_MANAGER_PLUGIN_NAME . '_OpenCitations_Token';

    /** @var true Whether debugging mode is activated, careful with exposing secrets! */
    public const isDebugMode = true;
    /** @var true Whether testing mode. If this is enabled, classes in "tests/classes" are used. */
    public bool $isTestMode = true;
    /** @var array These are parameters which are used in templates in the front en backend. @see initPlugin() */
    public array $templateParameters = [];

    /** @copydoc Plugin::register */
    public function register($category, $path, $mainContextId = null): bool
    {
        if (parent::register($category, $path, $mainContextId)) {

            if ($this->getEnabled()) {

                HookRegistry::register('AcronPlugin::parseCronTab', function ($hookName, $args) {
                    $taskFilesPath =& $args[0];
                    $taskFilesPath[] = $this->getPluginPath() . DIRECTORY_SEPARATOR . 'scheduledTasks.xml';
                    return false;
                });

                $pluginSchema = new PluginSchema();
                HookRegistry::register('Schema::get::publication', function ($hookName, $args) use ($pluginSchema) {
                    $pluginSchema->addToSchemaPublication($hookName, $args);
                });
                HookRegistry::register('Schema::get::author', function ($hookName, $args) use ($pluginSchema) {
                    $pluginSchema->addToSchemaAuthor($hookName, $args);
                });

                $this->initPlugin($category, $path, $mainContextId);

                $submissionWizard = new SubmissionWizard($this);
                HookRegistry::register('Template::SubmissionWizard::Section', function ($hookName, $args) use ($submissionWizard) {
                    $submissionWizard->execute($hookName, $args);
                });

                $workflowTab = new WorkflowTab($this);
                HookRegistry::register('Template::Workflow', function ($hookName, $args) use ($workflowTab) {
                    $workflowTab->execute($hookName, $args);
                });

                $workflowSave = new WorkflowSave($this);
                HookRegistry::register('Publication::edit', function ($hookName, $args) use ($workflowSave) {
                    $workflowSave->execute($hookName, $args);
                });

                $articlePage = new ArticleView($this);
                HookRegistry::register('TemplateManager::display', function ($hookName, $args) use ($articlePage) {
                    $articlePage->execute($hookName, $args);
                });

                HookRegistry::register('Dispatcher::dispatch', function ($hookName, $args) {
                    try {
                        $request = $args;
                        $router = $request->getRouter();

                        if ($router instanceof APIRouter
                            && str_contains($request->getRequestPath(), 'api/v1/' . CITATION_MANAGER_PLUGIN_NAME)
                        ) {
                            $handler = new PluginAPIHandler($this);
                            $router->setHandler($handler);
                            $handler->getApp()->run();
                            exit;
                        }
                    } catch (Throwable $ex) {
                        error_log(__METHOD__ . ' ' . $ex->getMessage());
                    }

                    return false;
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
     * @return void
     */
    public function initPlugin($category, $path, $mainContextId = null): void
    {
        $request = $this->getRequest();
        $context = $request->getContext();

        $isTestMode = strtolower(Config::getVar(CITATION_MANAGER_PLUGIN_NAME, 'isTestMode'));
        if (!empty($isTestMode) && ($isTestMode === 'true' || $isTestMode === '1'))
            $this->isTestMode = true;

        $apiBaseUrl = '';
        if (!empty($context) && !empty($context->getData('urlPath'))) {
            $apiBaseUrl = $request->getDispatcher()->url(
                $request,
                ROUTE_API,
                $context->getData('urlPath'),
                '');
        }

        $this->templateParameters = [
            'locale' => '',
            'assetsUrl' => $request->getBaseUrl() . '/' . $this->getPluginPath() . '/assets',
            'apiBaseUrl' => $apiBaseUrl,
            'journalMetadata' => '',
            'authors' => '',
            'authorModel' => json_encode(ClassHelper::getClassAsArrayNullAssigned(new AuthorModel())),
            'publicationMetadata' => json_encode(ClassHelper::getClassAsArrayNullAssigned(new PublicationMetadata())),
            'structuredCitations' => '',
            'url' => [
                'doi' => Doi::prefix,
                'openAlex' => OpenAlex::prefix,
                'openCitations' => GitHubIssue::prefix,
                'orcid' => Orcid::prefix,
                'wikidata' => Wikidata::prefix
            ]
        ];
    }

    /** @copydoc Plugin::getActions() */
    public function getActions($request, $actionArgs): array
    {
        if (!$this->getEnabled()) return parent::getActions($request, $actionArgs);

        $actions = new Actions($this);
        return $actions->execute($request, $actionArgs, parent::getActions($request, $actionArgs));
    }

    /** @copydoc Plugin::manage() */
    public function manage($args, $request): JSONMessage
    {
        $manage = new Manage($this);
        return $manage->execute($args, $request);
    }

    /** @copydoc PKPPlugin::getDescription */
    public function getDescription(): string
    {
        return __('plugins.generic.citationManager.description');
    }

    /** @copydoc PKPPlugin::getDisplayName */
    public function getDisplayName(): string
    {
        return __('plugins.generic.citationManager.displayName');
    }

    public function getIsTestMode(): bool
    {
        return $this->isTestMode;
    }
}

class_alias('\CitationManagerPlugin', '\APP\plugins\generic\citationManager\CitationManagerPlugin');
