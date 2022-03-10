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

const OPTIMETA_CITATIONS_PARSED_KEY_DB = 'OptimetaCitations__CitationsParsed';
const OPTIMETA_CITATIONS_PARSED_KEY_FORM = 'OptimetaCitations__CitationsParsed';
const OPTIMETA_CITATIONS_API_ENDPOINT = 'OptimetaCitations';
const OPTIMETA_CITATIONS_PUBLICATION_FORM = "OptimetaCitations_PublicationForm";

require_once ( __DIR__ . '/vendor/autoload.php');

import('lib.pkp.classes.plugins.GenericPlugin');
import('lib.pkp.classes.site.VersionCheck');
import('lib.pkp.classes.handler.APIHandler');

import('plugins.generic.optimetaCitations.classes.Components.Forms.PublicationForm');
import('plugins.generic.optimetaCitations.classes.Handler.OptimetaCitationsAPIHandler');
import('plugins.generic.optimetaCitations.classes.Parser.Parser');

use Optimeta\Citations\Components\Forms\PublicationForm;
use Optimeta\Citations\Parser\Parser;
use Optimeta\Citations\Handler\OptimetaCitationsAPIHandler;

class OptimetaCitationsPlugin extends GenericPlugin
{
    protected $citationsKeyDb = OPTIMETA_CITATIONS_PARSED_KEY_DB;
    protected $citationsKeyForm = OPTIMETA_CITATIONS_PARSED_KEY_FORM;
    protected $apiEndpoint = OPTIMETA_CITATIONS_API_ENDPOINT;
    protected $publicationForm = OPTIMETA_CITATIONS_PUBLICATION_FORM;

    protected $ojsVersion = '3.3.0.0';

    protected $templateParameters = [
        'pluginStylesheetURL' => '',
        'pluginJavaScriptURL' => '',
        'pluginImagesURL' => '',
        'citationsKeyForm' => '',
        'pluginApiUrl' => ''
    ];

    /**
     * @copydoc Plugin::register
     */
    public function register($category, $path, $mainContextId = null): bool
    {
        // Register the plugin even when it is not enabled
        $success = parent::register($category, $path);

        $this->ojsVersion = VersionCheck::getCurrentCodeVersion()->getVersionString(false);

        // Current Request / Context
        $request = $this->getRequest();

        // Fill generic template parameters
        $this->templateParameters['pluginStylesheetURL'] = $request->getBaseUrl() . '/' . $this->getPluginPath() . '/css';
        $this->templateParameters['pluginJavaScriptURL'] = $request->getBaseUrl() . '/' . $this->getPluginPath() . '/js';
        $this->templateParameters['pluginImagesURL'] = $request->getBaseUrl() . '/' . $this->getPluginPath() . '/images';
        $this->templateParameters['citationsKeyForm'] = $this->citationsKeyForm;
        $this->templateParameters['pluginApiUrl'] = '';

        if ($success && $this->getEnabled())
        {
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

        $stateVersionDependentName = 'state';
        if (strstr($this->ojsVersion, '3.2.1')) {
            $stateVersionDependentName = 'workflowData';
        }

        $state = $templateMgr->getTemplateVars($stateVersionDependentName);
        $state['components'][$this->publicationForm] = $form->getConfig();
        $templateMgr->assign($stateVersionDependentName, $state);

        $publicationDao = DAORegistry::getDAO('PublicationDAO');
        $publication = $publicationDao->getById($submissionId);
        $citationsParsed = $publication->getData($this->citationsKeyDb);
        $citationsRaw = $publication->getData('citationsRaw');
        if ($citationsParsed == '' && $citationsRaw != '') {
            $parser = new Parser($citationsRaw);
            $citationsParsed = $parser->getCitationsParsedJson();
        }
        if ($citationsParsed == null || $citationsParsed == '') {
            $citationsParsed = '[]';
        }

        $this->templateParameters['pluginApiUrl'] = $apiBaseUrl . $this->apiEndpoint;
        $this->templateParameters['submissionId'] = $submissionId;
        $this->templateParameters['citationsParsed'] = $citationsParsed;
        $this->templateParameters['citationsRaw'] = $citationsRaw;
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
        //todo: move to proper DAO/database table(s)
        $publication = $args[0];
        $request = $this->getRequest();

        if ($request->getuserVar($this->citationsKeyForm)) {
            $publication->setData(
                $this->citationsKeyDb,
                $request->getuserVar($this->citationsKeyForm));
        }
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

        $publicationDao = DAORegistry::getDAO('PublicationDAO');
        $submissionId = $request->getUserVar('submissionId');
        $publication = $publicationDao->getById($submissionId);
        $citationsParsed = $publication->getData($this->citationsKeyDb);
        $citationsRaw = $publication->getData('citationsRaw');
        if ($citationsParsed == '' && $citationsRaw != '') {
            $parser = new Parser($citationsRaw);
            $citationsParsed = $parser->getCitationsParsedJson();
        }
        if ($citationsParsed == null || $citationsParsed == '') {
            $citationsParsed = '[]';
        }

        $this->templateParameters['pluginApiUrl'] = $apiBaseUrl . $this->apiEndpoint;
        $this->templateParameters['submissionId'] = $submissionId;
        $this->templateParameters['citationsParsed'] = $citationsParsed;
        $this->templateParameters['citationsRaw'] = $citationsRaw;
        $templateMgr->assign($this->templateParameters);

        $templateMgr->display($this->getTemplateResource("submission/form/submissionWizard.tpl"));
    }

    /**
     * @param string $hookName
     * @param PKPRequest $request
     * @return bool
     * @brief execute api handler
     */
    public function apiHandler(string $hookName, PKPRequest $request): bool
    {
        $router = $request->getRouter();
        if ($router instanceof \APIRouter && strpos(' ' .
                $request->getRequestPath() . ' ', 'api/v1/' . $this->apiEndpoint) !== false) {
            $handler = new OptimetaCitationsAPIHandler($this);
            $router->setHandler($handler);
            $handler->getApp()->run();
            exit;
        }

        return false;
    }

    /* ********************** */
    /* Plugin required methods */
    /* ********************** */

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

    /* ********************** */
    /* Plugin required methods */
    /* ********************** */
}
