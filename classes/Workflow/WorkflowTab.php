<?php
/**
 * @file classes/Workflow/WorkflowTab.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class WorkflowTab
 * @brief Workflow Publication Tab
 */

namespace APP\plugins\generic\citationManager\classes\Workflow;

use APP\plugins\generic\citationManager\CitationManagerPlugin;
use APP\plugins\generic\citationManager\classes\DataModels\Metadata\MetadataAuthor;
use APP\plugins\generic\citationManager\classes\Db\PluginDAO;
use Application;
use Author;
use Exception;
use Publication;

class WorkflowTab
{
    /** @var CitationManagerPlugin */
    public CitationManagerPlugin $plugin;

    /** @param CitationManagerPlugin $plugin */
    public function __construct(CitationManagerPlugin $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Show tab under Publications
     *
     * @param string $hookName
     * @param array $args [string, TemplateManager]
     * @return void
     * @throws Exception
     */
    public function execute(string $hookName, array $args): void
    {
        /* @var Publication $publication */
        $templateMgr = &$args[1];

        $request = $this->plugin->getRequest();
        $context = $request->getContext();
        $submission = $templateMgr->getTemplateVars('submission');
        $submissionId = $submission->getId();
        $publication = $submission->getLatestPublication();
        $publicationId = $publication->getId();
        $locale = $publication->getData('locale');

        $apiBaseUrl = $request->getDispatcher()->url(
            $request,
            ROUTE_API,
            $context->getData('urlPath'),
            '');

        $locales = $context->getSupportedLocaleNames();
        $locales = array_map(
            fn(string $locale, string $name) => ['key' => $locale, 'label' => $name],
            array_keys($locales), $locales);

        $form = new WorkflowForm(
            CitationManagerPlugin::CITATION_MANAGER_STRUCTURED_CITATIONS_FORM,
            'PUT',
            $apiBaseUrl . 'submissions/' . $submissionId . '/publications/' . $publicationId,
            $locales);

        $state = $templateMgr->getTemplateVars('state');
        $state['components'][CitationManagerPlugin::CITATION_MANAGER_STRUCTURED_CITATIONS_FORM] = $form->getConfig();
        $templateMgr->assign('state', $state);

        $pluginDao = new PluginDAO();

        // author(s)
        $authors = [];
        /* @var Author $author */
        foreach ($publication->getData('authors') as $id => $author) {
            $author->setData(CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHOR,
                $pluginDao->getMetadataAuthor($author->getId(), $author));
            $authors[] = $author;
        }

        $this->plugin->templateParameters['metadataJournal'] = json_encode($pluginDao->getMetadataJournal($context->getId()));
        $this->plugin->templateParameters['authors'] = json_encode($authors);
        $this->plugin->templateParameters['metadataPublication'] = json_encode($pluginDao->getMetadataPublication($publicationId));
        $this->plugin->templateParameters['citationsStructured'] = json_encode($pluginDao->getCitations($publicationId));
        $templateMgr->assign($this->plugin->templateParameters);

        $templateMgr->display($this->plugin->getTemplateResource("workflowTab.tpl"));
    }
}
