<?php
/**
 * @file classes/Workflow/WorkflowSave.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class WorkflowSave
 * @brief Workflow WorkflowSave
 */

namespace APP\plugins\generic\citationManager\classes\Workflow;

use APP\plugins\generic\citationManager\CitationManagerPlugin;

class WorkflowSave
{
    /** @var CitationManagerPlugin */
    public CitationManagerPlugin $plugin;

    /** @param CitationManagerPlugin $plugin */
    public function __construct(CitationManagerPlugin $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Process data from post/put
     *
     * @param string $hookname
     * @param array $args [ Publication, parameters/publication, Request ]
     */
    public function execute(string $hookname, array $args): void
    {
        $publication = $args[0];
        $params = $args[2];
        $request = $this->plugin->getRequest();

        // structuredCitations

        // submissionWizard
        $structuredCitations = $request->getuserVar(CitationManagerPlugin::CITATION_MANAGER_CITATIONS_STRUCTURED);

        // publicationTab
        if (array_key_exists(CitationManagerPlugin::CITATION_MANAGER_CITATIONS_STRUCTURED, $params)) {
            if (!empty($params[CitationManagerPlugin::CITATION_MANAGER_CITATIONS_STRUCTURED])) {
                $structuredCitations = $params[CitationManagerPlugin::CITATION_MANAGER_CITATIONS_STRUCTURED];
            }
        }
        $publication->setData(CitationManagerPlugin::CITATION_MANAGER_CITATIONS_STRUCTURED, $structuredCitations);

        // publicationMetadata

        // submissionWizard
        $publicationMetadata = $request->getuserVar(CitationManagerPlugin::CITATION_MANAGER_METADATA_PUBLICATION);

        // publicationTab
        if (array_key_exists(CitationManagerPlugin::CITATION_MANAGER_METADATA_PUBLICATION, $params)) {
            if (!empty($params[CitationManagerPlugin::CITATION_MANAGER_CITATIONS_STRUCTURED])) {
                $publicationMetadata = $params[CitationManagerPlugin::CITATION_MANAGER_METADATA_PUBLICATION];
            }
        }
        $publication->setData(CitationManagerPlugin::CITATION_MANAGER_METADATA_PUBLICATION, $publicationMetadata);
    }
}
