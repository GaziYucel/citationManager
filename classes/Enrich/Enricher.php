<?php
/**
 * @file plugins/generic/optimetaCitations/classes/Enrich/Enricher.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Enricher
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Main Enricher class
 */

namespace APP\plugins\generic\optimetaCitations\classes\Enrich;

use APP\plugins\generic\optimetaCitations\classes\Model\CitationModel;
use APP\plugins\generic\optimetaCitations\OptimetaCitationsPlugin;

class Enricher
{
    /**
     * @var OptimetaCitationsPlugin
     */
    public OptimetaCitationsPlugin $plugin;

    public function __construct(OptimetaCitationsPlugin $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Enrich citations and save results to citations
     * @param array $citationsParsed
     * @return array
     */
    public function executeAndReturnCitations(array $citationsParsed): array
    {
        $citations = [];

        // return if input is empty
        if (sizeof($citationsParsed) == 0) {
            return $citations;
        }

        // loop through citations and enrich every citation
        foreach ($citationsParsed as $index => $row) {
            if (is_object($row) || is_array($row)) {
                $citation = new CitationModel();

                // convert array to object
                foreach ($row as $key => $value) {
                    if (property_exists($citation, $key)) {
                        $citation->$key = $value;
                    }
                }

                // skip iteration if isProcessed or DOI empty
                if ($citation->isProcessed || empty($citation->doi)) {
                    $citations[] = (array)$citation;
                    continue;
                }

                // OpenAlex Work
                $objOpenAlex = new OpenAlex($this->plugin);
                $citation = $objOpenAlex->getWork($citation);

                // WikiData
                $objWikiData = new WikiData($this->plugin);
                $citation = $objWikiData->getItem($citation);

                // Orcid
                $objOrcid = new \APP\plugins\generic\optimetaCitations\classes\Orcid\Enrich($this->plugin);
                $citation = $objOrcid->getAuthors($citation);

                // push to citations enriched array
                $citations[] = (array)$citation;
            }
        }

        return $citations;
    }
}
