<?php
/**
 * @file plugins/generic/optimetaCitation/OptimetaCitationsPlugin.inc.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class OptimetaCitationsPlugin
 * @ingroup plugins_generic_optimetacitation
 *
 * @brief Wrapper for the Optimeta Citation plugin.
 */

import('lib.pkp.classes.plugins.GenericPlugin');

class OptimetaCitationsPlugin extends GenericPlugin
{
    private $citationsKeyName = 'optimetaCitationsPlugin::citations';
    private $citationsDefaultValue = '{}';

    // @todo: todo
    // @fixme: fixme

    /**
     * @copydoc Plugin::register
     */
    public function register($category, $path, $mainContextId = NULL)
    {
        // Register the plugin even when it is not enabled
        $success = parent::register($category, $path);
        if ($success && $this->getEnabled()) {

            // Hooks for changing the frontend Submit an Article 3. Enter Metadata
            HookRegistry::register('Templates::Submission::SubmissionMetadataForm::AdditionalMetadata', array($this, 'extendSubmissionWizardForm'));

            // Hooks for changing the Submission > Publication Edit Metadata
            HookRegistry::register('Template::Workflow::Publication', array($this, 'extendSubmissionEditForm'));

            // $request = Application::get()->getRequest();
            // $templateMgr = TemplateManager::getManager($request);

            // main js scripts
            // $templateMgr->assign('submissionMetadataFormFieldsJS', $request->getBaseUrl() . '/' . $this->getPluginPath() . '/js/submissionWizardForm.js');
        }
        return $success;
    }

    public function extendSubmissionWizardForm($hookName, $params)
    {
        $templateMgr = &$params[1];
        $output = &$params[2];

        $request = Application::get()->getRequest();
        $context = $request->getContext();

        $publicationDao = DAORegistry::getDAO('PublicationDAO');
        $submissionId = $request->getUserVar('submissionId');
        $publication = $publicationDao->getById($submissionId);

        $citations = $publication->getData($this->citationsKeyName);

        if($citations == null || $citations == '')
        {
            $citations = $this->citationsDefaultValue;
        }

        $templateMgr->assign('submissionWizardFormTitle', 'submissionWizardFormTitle [assigned dynamically]');
        $templateMgr->assign('citations', $citations);

        // extend original template by the additional template
        $output .= $templateMgr->fetch($this->getTemplateResource('submission/form/submissionWizardForm.tpl'));

        return false;
    }

    public function extendSubmissionEditForm($hookName, $params)
    {
        $templateMgr = &$params[1];
        $output = &$params[2];

        $templateMgr->assign('submissionEditFormTitle', 'submissionEditFormTitle [assigned dynamically]');

        // extend original template by the additional template
        $output .= $templateMgr->fetch($this->getTemplateResource('submission/form/submissionEditForm.tpl'));

        return false;
    }

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

}
