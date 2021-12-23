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

import('lib.pkp.classes.plugins.GenericPlugin');
import('plugins.generic.optimetaCitations.classes.components.forms.PublicationOptimetaCitationsForm');
import('plugins.generic.optimetaCitations.classes.OptimetaCitationsParser');

use \PKP\components\forms\FormComponent;

class OptimetaCitationsPlugin extends GenericPlugin
{
    private $citationsKeyDb      = 'optimetaCitations::parsedCitations';
    private $citationsKeyForm    = 'optimetaCitations__parsedCitations';

    /**
    * @copydoc Plugin::register
    */
    public function register($category, $path, $mainContextId = null)
    {
        // Register the plugin even when it is not enabled
        $success = parent::register($category, $path);

        if ($success && $this->getEnabled()) {

            // Is triggered with every request from anywhere
            HookRegistry::register('Schema::get::publication', array($this, 'addToSchema'));

            // is triggered only on these hooks
            HookRegistry::register('Template::Workflow::Publication', array($this, 'publicationTabCitations'));
            HookRegistry::register('Publication::edit', array($this, 'publicationTabCitationsEdit'));

        }

        return $success;
    }

    /**
     * Add a property to the publication schema
     *
     * @param $hookName string `Schema::get::publication`
     * @param $args [[
     * @option object Publication schema
     * ]]
     */
    public function addToSchema(string $hookName, array $args): void {

        $schema = $args[0];

        $properties = '{
            "type": "string",
            "multilingual": false,
            "apiSummary": true,
            "validation": [ "nullable" ]
        }';

        $schema->properties->{ $this->citationsKeyDb } = json_decode($properties);

    }

    /**
     * @param string $hookname
     * @param array $args [string, TemplateManager]
     * @brief Show tab under Publications
     */
    public function publicationTabCitations(string $hookName, array $args): void
    {
        $templateMgr = &$args[1];

        $request = $this->getRequest();
        $context = $request->getContext();
        $submission = $templateMgr->getTemplateVars('submission');
        $latestPublication = $submission->getLatestPublication();
        $latestPublicationApiUrl = $request->getDispatcher()->url(
            $request,
            ROUTE_API,
            $context->getData('urlPath'),
            'submissions/' . $submission->getId() . '/publications/' . $latestPublication->getId());

        $form = new PublicationOptimetaCitationsForm($latestPublicationApiUrl, $latestPublication);

        $state = $templateMgr->getTemplateVars('state');
        $state['components'][FORM_PUBLICATION_OPTIMETA_CITATIONS] = $form->getConfig();

        $templateMgr->assign('state', $state);

        $publicationDao = DAORegistry::getDAO('PublicationDAO');
        $publication = $publicationDao->getById($submission->getId());

        if(isset($_POST[$this->citationsKeyForm])){
            $publication->setData(
                $this->citationsKeyDb,
                $_POST[$this->citationsKeyForm]);
            DebugToFile($this->citationsKeyDb . ' saved');
        }

        $citationsRaw = $publication->getData('citationsRaw');
        $parsedCitationsDb = $publication->getData($this->citationsKeyDb);
        $parsedCitations = $parsedCitationsDb;

        if($parsedCitations == ''){
            $parser = new OptimetaCitationsParser($citationsRaw);
            $parsedCitations = $parser->getParsedCitationsJson();
        }

        $templateMgr->assign(array(
            'pluginStylesheetURL' => $this->getStylesheetUrl($request),
            'pluginJavaScriptURL' => $this->getJavaScriptURL($request),
            'pluginImagesURL' => $this->getImagesURL($request),
            'parsedCitationsDb' => $parsedCitationsDb,
            'parsedCitations' => $parsedCitations,
            'citationsRaw' => $citationsRaw,
            'citationsKeyForm' => $this->citationsKeyForm
            ));

        $templateMgr->display($this->getTemplateResource("submission/form/submissionEditForm.tpl"));

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
    public function  publicationTabCitationsEdit(string $hookname, array $args): void {

        $publication = $args[0];
        $params = $args[2];

        if (!array_key_exists($this->citationsKeyForm, $params)) {
            DebugToFile($this->citationsKeyForm . ' not found');
            //return;
        }

        $value = $params[$this->citationsKeyForm];

        $publication->setData($this->citationsKeyDb, $value);
    }

    /* Plugin related methods */

    /**
     * @copydoc PKPPlugin::getDisplayName
     */
    public function getDisplayName()
    {
        return __('plugins.generic.optimetaCitationsPlugin.name');
    }

    /**
     * @copydoc PKPPlugin::getDescription
     */
    public function getDescription()
    {
        return __('plugins.generic.optimetaCitationsPlugin.description');
    }

    /**
     * Get the current context ID or the site-wide context ID (0) if no context
     * can be found.
     */
    function getCurrentContextId()
    {
        $context = Application::get()->getRequest()->getContext();
        return is_null($context) ? 0 : $context->getId();
    }

    /**
     * Get the JavaScript URL for this plugin.
     */
    function getJavaScriptURL($request)
    {
        return $request->getBaseUrl() . '/' . $this->getPluginPath() . '/js';
    }

    /**
     * Get the Images URL for this plugin.
     */
    function getImagesURL($request)
    {
        return $request->getBaseUrl() . '/' . $this->getPluginPath() . '/images';
    }

    /**
     * Get the Stylesheet URL for this plugin.
     */
    function getStylesheetUrl($request)
    {
        return $request->getBaseUrl() . '/' . $this->getPluginPath() . '/css';
    }

}
