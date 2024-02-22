<?php
/**
 * @file classes/Handlers/EnricherHandler.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yücel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class EnricherHandler
 * @ingroup plugins_generic_citationmanager
 *
 * @brief EnricherHandler class
 */

namespace APP\plugins\generic\citationManager\classes\Handlers;

use APP\plugins\generic\citationManager\classes\DataModels\CitationModel;
use APP\plugins\generic\citationManager\classes\DataModels\MetadataJournal;
use APP\plugins\generic\citationManager\classes\Helpers\ClassHelper;
use APP\plugins\generic\citationManager\classes\Helpers\LogHelper;
use APP\plugins\generic\citationManager\CitationManagerPlugin;
use APP\plugins\generic\citationManager\classes\OpenAlex\Enrich as OpenAlexEnrich;
use APP\plugins\generic\citationManager\classes\Wikidata\Enrich as WikidataEnrich;
use APP\plugins\generic\citationManager\classes\Orcid\Enrich as OrcidEnrich;

class EnricherHandler
{
    /** @var CitationManagerPlugin */
    public CitationManagerPlugin $plugin;

    /**
     * Constructor
     *
     * @param CitationManagerPlugin $plugin
     */
    public function __construct(CitationManagerPlugin $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Enrich citations and save results to citations
     *
     * @param array $citationsIn
     * @return array
     */
    public function execute(array $citationsIn): array
    {
        // return if input is empty
        if (empty($citationsIn)) return [];

        $citations = [];

        // loop through citations and enrich every citation
        foreach ($citationsIn as $row) {
            // skip if not object nor array
            if (!is_object($row) && !is_array($row)) continue;

            /* @var CitationModel $citation */
            $citation = ClassHelper::getClassWithValuesAssigned(new CitationModel(), $row);

            LogHelper::logInfo([ __METHOD__ => $citation ]);

            // skip iteration if isProcessed or DOI empty
            if ($citation->isProcessed || empty($citation->doi)) {
                $citations[] = (array)$citation;
                continue;
            }

            // OpenAlex Work
            $objOpenAlex = new OpenAlexEnrich($this->plugin);
            $citation = $objOpenAlex->process($citation);

            // Wikidata
            $objWikidata = new WikidataEnrich($this->plugin);
            $citation = $objWikidata->process($citation);

            // Orcid
            $objOrcid = new OrcidEnrich($this->plugin);
            $citation = $objOrcid->process($citation);

            // push to citations enriched array
            $citations[] = (array)$citation;
        }

        return $citations;
    }
}
