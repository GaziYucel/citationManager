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

const OptimetaCitations_ParsedKeyDb = 'OptimetaCitations__CitationsParsed';
const OptimetaCitations_ParsedKeyForm = 'OptimetaCitations__CitationsParsed';
const OptimetaCitations_ApiEndpoint = 'OptimetaCitations';
const OptimetaCitations_PublicationForm = "OptimetaCitations_PublicationForm";

import('lib.pkp.classes.plugins.GenericPlugin');
import('lib.pkp.classes.site.VersionCheck');
import('lib.pkp.classes.handler.APIHandler');

import('plugins.generic.optimetaCitations.classes.components.forms.PublicationOptimetaCitationsForm');
import('plugins.generic.optimetaCitations.classes.handler.OptimetaCitationsAPIHandler');
import('plugins.generic.optimetaCitations.classes.parser.OptimetaCitationsParser');

class OptimetaCitationsPlugin extends GenericPlugin
{
    private $citationsKeyDb = OptimetaCitations_ParsedKeyDb;
    private $citationsKeyForm = OptimetaCitations_ParsedKeyForm;
    private $apiEndpoint = OptimetaCitations_ApiEndpoint;

    private $version = '0.0.0.0';

    /**
     * @copydoc Plugin::register
     */
    public function register($category, $path, $mainContextId = null)
    {
        // Register the plugin even when it is not enabled
        $success = parent::register($category, $path);

        $this->version = VersionCheck::getCurrentCodeVersion()->getVersionString(false);

        if ($success && $this->getEnabled())
        {
            // Is triggered with every request from anywhere
            HookRegistry::register('Schema::get::publication', array($this, 'addToSchema'));

            // Is triggered only on these hooks
            HookRegistry::register('Templates::Submission::SubmissionMetadataForm::AdditionalMetadata', array($this, 'submissionWizard'));
            HookRegistry::register('Template::Workflow::Publication', array($this, 'publicationTab'));
            HookRegistry::register('Publication::edit', array($this, 'publicationTabSave'));

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

        $form = new PublicationOptimetaCitationsForm(
            $apiBaseUrl . 'submissions/' . $submissionId . '/publications/' . $latestPublication->getId(),
            $latestPublication,
            __('plugins.generic.optimetaCitationsPlugin.publication.success'));

        $stateVersionDependentName = 'state';
        if (strstr($this->version, '3.2.1')) {
            $stateVersionDependentName = 'workflowData';
        }

        $state = $templateMgr->getTemplateVars($stateVersionDependentName);
        $state['components'][OptimetaCitations_PublicationForm] = $form->getConfig();
        $templateMgr->assign($stateVersionDependentName, $state);

        $publicationDao = DAORegistry::getDAO('PublicationDAO');
        $publication = $publicationDao->getById($submissionId);
        $citationsParsed = $publication->getData($this->citationsKeyDb);
        $citationsRaw = $publication->getData('citationsRaw');
        if ($citationsParsed == '' && $citationsRaw != '') {
            $parser = new OptimetaCitationsParser($citationsRaw);
            $citationsParsed = $parser->getCitationsParsedJson();
        }
        if ($citationsParsed == null || $citationsParsed == '') {
            $citationsParsed = '[]';
        }

        $templateMgr->assign(array(
            'pluginStylesheetURL' => $this->getStylesheetUrl($request),
            'pluginJavaScriptURL' => $this->getJavaScriptURL($request),
            'pluginImagesURL' => $this->getImagesURL($request),
            'submissionId' => $submissionId,
            'pluginApiParseUrl' => $apiBaseUrl . $this->apiEndpoint . '/parse',
            'citationsKeyForm' => $this->citationsKeyForm,
            'citationsParsed' => $citationsParsed,
            'citationsRaw' => $citationsRaw
        ));

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
    public function publicationTabSave(string $hookname, array $args): void
    {
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
            $parser = new OptimetaCitationsParser($citationsRaw);
            $citationsParsed = $parser->getCitationsParsedJson();
        }
        if ($citationsParsed == null || $citationsParsed == '') {
            $citationsParsed = '[]';
        }

        $templateMgr->assign(array(
            'pluginStylesheetURL' => $this->getStylesheetUrl($request),
            'pluginJavaScriptURL' => $this->getJavaScriptURL($request),
            'pluginImagesURL' => $this->getImagesURL($request),
            'pluginApiParseUrl' => $apiBaseUrl . $this->apiEndpoint . '/parse',
            'citationsKeyForm' => $this->citationsKeyForm,
            'citationsParsed' => $citationsParsed,
            'citationsRaw' => $citationsRaw
        ));

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
