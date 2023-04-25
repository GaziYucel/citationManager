<?php
/**
 * @file plugins/generic/optimetaCitations/classes/Enrich/WikiData.inc.php
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

namespace Optimeta\Citations\Enrich;

use Application;
use Optimeta\Citations\Model\CitationModel;
use Optimeta\Shared\Pid\Doi;
use Optimeta\Shared\WikiData\WikiDataBase;
use OptimetaCitationsPlugin;

class WikiData
{
    /**
     * Is this instance production
     * @var bool
     */
    protected bool $isProduction = false;

    public function __construct()
    {
        $plugin = new OptimetaCitationsPlugin();
        if ($plugin->getSetting($plugin->getCurrentContextId(), OPTIMETA_CITATIONS_IS_PRODUCTION_KEY) === 'true') {
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
        $plugin = new OptimetaCitationsPlugin();
        $context = Application::get()->getRequest()->getContext();
        $contextId = $context ? $context->getId() : CONTEXT_SITE;

        $objDoi = new Doi();
        $doi = $objDoi->removePrefixFromUrl($citation->doi);

        $wikiData = new WikiDataBase(
            !$this->isProduction,
            $plugin->getSetting($contextId, OPTIMETA_CITATIONS_WIKIDATA_USERNAME),
            $plugin->getSetting($contextId, OPTIMETA_CITATIONS_WIKIDATA_PASSWORD));

        $citation->wikidata_qid = $wikiData->getEntity($doi, '');

        return $citation;
    }
}
