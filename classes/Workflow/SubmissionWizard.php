<?php
/**
 * @file classes/Workflow/SubmissionWizard.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class SubmissionWizard
 * @brief Submission wizard
 */

namespace APP\plugins\generic\citationManager\classes\Workflow;

use APP\plugins\generic\citationManager\CitationManagerPlugin;
use APP\plugins\generic\citationManager\classes\Db\PluginDAO;

class SubmissionWizard
{
    /**@var CitationManagerPlugin */
    public CitationManagerPlugin $plugin;

    /** @param CitationManagerPlugin $plugin */
    public function __construct(CitationManagerPlugin $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Show citations part on step 3 in submission wizard
     *
     * @param string $hookname
     * @param array $args
     * @return void
     */
    public function execute(string $hookname, array $args): void
    {
        $templateMgr = &$args[1];

        $submission = $templateMgr->getTemplateVars('submission');
        $publication = $submission->getCurrentPublication();

        $pluginDao = new PluginDAO();
        $this->plugin->templateParameters['publicationMetadata'] =
            json_encode($pluginDao->getPublicationMetadata($publication->getId()));

        $this->plugin->templateParameters['structuredCitations'] =
            json_encode($pluginDao->getCitations($publication->getId()));

        $templateMgr->assign($this->plugin->templateParameters);

        $templateMgr->display($this->plugin->getTemplateResource("submissionWizard.tpl"));
    }
}