<?php
/**
 * @file CitationManagerPlugin.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class CitationManagerPlugin
 * @brief Plugin for structuring, enriching and depositing Citations from and to external services.
 */

namespace APP\plugins\generic\citationManager;

require_once(__DIR__ . '/vendor/autoload.php');

use APP\plugins\generic\citationManager\classes\Db\PluginSchema;
use APP\plugins\generic\citationManager\classes\FrontEnd\ArticleView;
use APP\plugins\generic\citationManager\classes\Handlers\PluginAPIHandler;
use APP\plugins\generic\citationManager\classes\Settings\Actions;
use APP\plugins\generic\citationManager\classes\Settings\Manage;
use APP\plugins\generic\citationManager\classes\Workflow\SubmissionWizard;
use APP\plugins\generic\citationManager\classes\Workflow\WorkflowSave;
use APP\plugins\generic\citationManager\classes\Workflow\WorkflowTab;
use Config;
use PKP\core\APIRouter;
use PKP\core\JSONMessage;
use PKP\plugins\GenericPlugin;
use PKP\plugins\Hook;
use PKP\security\Role;
use Throwable;

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
    public const CITATION_MANAGER_CITATIONS_STRUCTURED_FORM = CITATION_MANAGER_PLUGIN_NAME . '_CitationsStructuredFrom';
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
    /** @var array Roles which can access PluginApiHandler */
    public const apiRoles = [Role::ROLE_ID_MANAGER, Role::ROLE_ID_SUB_EDITOR, Role::ROLE_ID_ASSISTANT, Role::ROLE_ID_REVIEWER, Role::ROLE_ID_AUTHOR];

    /** @copydoc Plugin::register */
    public function register($category, $path, $mainContextId = null): bool
    {
        if (parent::register($category, $path, $mainContextId)) {

            if ($this->getEnabled()) {

                Hook::add('AcronPlugin::parseCronTab', function ($hookName, $args) {
                    $taskFilesPath =& $args[0];
                    $taskFilesPath[] = $this->getPluginPath() . DIRECTORY_SEPARATOR . 'scheduledTasks.xml';
                    return false;
                });

                $pluginSchema = new PluginSchema();
                Hook::add('Schema::get::publication', function ($hookName, $args) use ($pluginSchema) {
                    $pluginSchema->addToSchemaPublication($hookName, $args);
                });
                Hook::add('Schema::get::author', function ($hookName, $args) use ($pluginSchema) {
                    $pluginSchema->addToSchemaAuthor($hookName, $args);
                });
                Hook::add('Schema::get::context', function ($hookName, $args) use ($pluginSchema) {
                    $pluginSchema->addToSchemaContext($hookName, $args);
                });

                $submissionWizard = new SubmissionWizard($this);
                Hook::add('Template::SubmissionWizard::Section', function ($hookName, $args) use ($submissionWizard) {
                    $submissionWizard->execute($hookName, $args);
                });

                $workflowTab = new WorkflowTab($this);
                Hook::add('Template::Workflow', function ($hookName, $args) use ($workflowTab) {
                    $workflowTab->execute($hookName, $args);
                });

                $workflowSave = new WorkflowSave($this);
                Hook::add('Publication::edit', function ($hookName, $args) use ($workflowSave) {
                    $workflowSave->execute($hookName, $args);
                });

                $articlePage = new ArticleView($this);
                Hook::add('TemplateManager::display', function ($hookName, $args) use ($articlePage) {
                    $articlePage->execute($hookName, $args);
                });

                Hook::add('Dispatcher::dispatch', function ($hookName, $args) {
                    try {
                        $request = $args[0];
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

    /**
     * Get isDebugMode from config, return false if setting not present
     * @return bool
     */
    public static function isDebugMode(): bool
    {
        $config_value = Config::getVar(CITATION_MANAGER_PLUGIN_NAME, 'isDebugMode');

        if (!empty($config_value)
            && (strtolower($config_value) === 'true' || (string)$config_value === '1')
        ) {
            return true;
        }

        return false;
    }
}

// For backwards compatibility -- expect this to be removed approx. OJS/OMP/OPS 3.6
if (!PKP_STRICT_MODE) {
    class_alias('\APP\plugins\generic\citationManager\CitationManagerPlugin', '\CitationManagerPlugin');
}
