<?php
/**
 * @file plugins/generic/optimetaCitations/classes/Enrich/WikiData.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class WikiData
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief WikiData class for WikiData
 */

namespace APP\plugins\generic\optimetaCitations\classes\Enrich;

use APP\plugins\generic\optimetaCitations\OptimetaCitationsPlugin;
use Application;
use APP\plugins\generic\optimetaCitations\classes\Model\CitationModel;
use APP\plugins\generic\optimetaCitations\classes\Pid\Doi;
use Optimeta\Shared\WikiData\WikiDataBase;

class WikiData
{
    /**
     * @var OptimetaCitationsPlugin
     */
    public OptimetaCitationsPlugin $plugin;

    /**
     * Is this instance production
     * @var bool
     */
    protected bool $isProduction = false;

    public function __construct(OptimetaCitationsPlugin $plugin)
    {
        $this->plugin = $plugin;

        if ($this->plugin->getSetting($plugin->getCurrentContextId(),
                $this->plugin::OPTIMETA_CITATIONS_IS_PRODUCTION_KEY) === 'true') {
            $this->isProduction = true;
        }
    }

    /**
     * Get information from Wikidata and return as CitationModel
     * @param CitationModel $citation
     * @return CitationModel
     */
    public function getItem(CitationModel $citation): CitationModel
    {
        $context = Application::get()->getRequest()->getContext();
        $contextId = $context ? $context->getId() : Application::CONTEXT_SITE;

        $objDoi = new Doi();
        $doi = $objDoi->removePrefixFromUrl($citation->doi);

        $wikiData = new WikiDataBase(
            !$this->isProduction,
            $this->plugin->getSetting($contextId, $this->plugin::OPTIMETA_CITATIONS_WIKIDATA_USERNAME),
            $this->plugin->getSetting($contextId, $this->plugin::OPTIMETA_CITATIONS_WIKIDATA_PASSWORD));

        $citation->wikidata_qid = $wikiData->getQidWithDoi($doi);

        if (!empty($citation->wikidata_qid)) {
            $citation->wikidata_url = $this->plugin::OPTIMETA_CITATIONS_WIKIDATA_URL_TEST . '/' . $citation->wikidata_qid;
            if (!$this->isProduction)
                $citation->wikidata_url = $this->plugin::OPTIMETA_CITATIONS_WIKIDATA_URL_TEST . '/' . $citation->wikidata_qid;
        }

        return $citation;
    }
}
