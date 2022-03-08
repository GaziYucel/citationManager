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

//require_once ( __DIR__ . '/vendor/autoload.php');

import('plugins.generic.optimetaCitations.OptimetaCitationsPluginBase');

import('lib.pkp.classes.plugins.GenericPlugin');
import('lib.pkp.classes.site.VersionCheck');
import('lib.pkp.classes.handler.APIHandler');

import('plugins.generic.optimetaCitations.classes.Components.Forms.PublicationForm');
import('plugins.generic.optimetaCitations.classes.Handler.OptimetaCitationsAPIHandler');
import('plugins.generic.optimetaCitations.classes.Parser.Parser');

use Optimeta\Citations\Components\Forms\PublicationForm;
use Optimeta\Citations\Parser\Parser;
use Optimeta\Citations\Handler\OptimetaCitationsAPIHandler;

class OptimetaCitationsPlugin extends OptimetaCitationsPluginBase
{
    /**
     * @copydoc Plugin::register
     */
    public function register($category, $path, $mainContextId = null)
    {
        // Register the plugin even when it is not enabled
        $success = parent::register($category, $path);

        if ($success && $this->getEnabled())
        {
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
     * @desc Show tab under Publications
     * @param string $hookName
     * @param array $args
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
        $apiBaseUrl = $dispatcher->url($request, ROUTE_API, $context->getData('urlPath'), '');

        $form = new PublicationForm(
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
            $parser = new Parser($citationsRaw);
            $citationsParsed = $parser->getCitationsParsedJson();
        }
        if ($citationsParsed == null || $citationsParsed == '') {
            $citationsParsed = '[]';
        }

        $this->templateParameters['submissionId'] = $submissionId;
        $this->templateParameters['citationsParsed'] = $citationsParsed;
        $this->templateParameters['citationsRaw'] = $citationsRaw;
        $templateMgr->assign($this->templateParameters);

        $templateMgr->display($this->getTemplateResource("submission/form/publicationTab.tpl"));
    }

    /**
     * @desc Process data from post/put
     * @param string $hookname
     * @param array $args [
     *   Publication -> new publication
     *   Publication
     *   array parameters/publication properties to be saved
     *   Request
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
     * @desc show citations part on step 3 in submission wizard
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

        $this->templateParameters['submissionId'] = $submissionId;
        $this->templateParameters['citationsParsed'] = $citationsParsed;
        $this->templateParameters['citationsRaw'] = $citationsRaw;
        $templateMgr->assign($this->templateParameters);

        $templateMgr->display($this->getTemplateResource("submission/form/submissionWizard.tpl"));
    }

    /**
     * @desc Execute api handler
     * @param string $hookName
     * @param PKPRequest $request
     * @return bool
     * @throws Throwable
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
}
