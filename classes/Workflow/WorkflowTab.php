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

use APP\author\Author;
use APP\plugins\generic\citationManager\CitationManagerPlugin;
use APP\plugins\generic\citationManager\classes\Db\PluginDAO;
use APP\plugins\generic\citationManager\classes\Helpers\LogHelper;
use APP\plugins\generic\citationManager\classes\PID\Orcid;
use APP\publication\Publication;
use Exception;
use PKP\core\PKPApplication;

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

        $apiBaseUrl = $request->getDispatcher()->url(
            $request,
            PKPApplication::ROUTE_API,
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

        /* @var Author $author */
        $authorsA = [];
        $authors = $publication->getData('authors');
        foreach ($authors as $id => $author) {
            $authorA = (array)$author->_data;
            $authorA['orcid'] = Orcid::removePrefix($authorA['orcid']);
            $authorA['authorMetadata'] = json_decode($authorA[CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHORS]);

            $authorsA[] = $authorA;
        }

        $this->plugin->templateParameters['locale'] = json_encode($publication->getDefaultLocale());
        $this->plugin->templateParameters['journalMetadata'] = json_encode($pluginDao->getJournalMetadata($publicationId));
        $this->plugin->templateParameters['authors'] = json_encode($authorsA);

        $this->plugin->templateParameters['publicationMetadata'] = json_encode($pluginDao->getPublicationMetadata($publicationId));
        $this->plugin->templateParameters['structuredCitations'] = json_encode($pluginDao->getCitations($publicationId));

        $templateMgr->assign($this->plugin->templateParameters);

        $templateMgr->display($this->plugin->getTemplateResource("publicationTab.tpl"));
    }
}
