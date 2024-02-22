<?php
/**
 * @file classes/Handlers/StructurerHandler.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yücel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class StructurerHandler
 * @ingroup plugins_generic_citationmanager
 *
 * @brief StructurerHandler class for extracting PID's, e.g. DOI, Handle, ...
 */

namespace APP\plugins\generic\citationManager\classes\Handlers;

use APP\plugins\generic\citationManager\classes\DataModels\CitationModel;
use APP\plugins\generic\citationManager\classes\Helpers\StringHelper;
use APP\plugins\generic\citationManager\CitationManagerPlugin;

class StructurerHandler
{
    /** @var CitationManagerPlugin */
    public CitationManagerPlugin $plugin;

    /**
     * Constructor.
     *
     * @param CitationManagerPlugin $plugin
     */
    public function __construct(CitationManagerPlugin $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Parse and structure citations
     *
     * @param string $citationsRaw
     *
     * @return array Array of CitationModels, e.g. [ { CitationModel }, ... ]
     */
    public function execute(string $citationsRaw): array
    {
        if (empty($citationsRaw)) return [];

        // clean whitespace, slashes, line endings, line break
        $citationsRaw = StringHelper::trim($citationsRaw);
        $citationsRaw = StringHelper::stripSlashes($citationsRaw);
        $citationsRaw = StringHelper::normalizeLineEndings($citationsRaw);
        $citationsRaw = StringHelper::trim($citationsRaw, "\n");

        // return if input is empty
        if (empty($citationsRaw)) return [];

        $citations = [];

        // loop through citations and fill every citation->raw
        foreach (explode("\n", $citationsRaw) as $index => $rowRaw) {
            $citation = new CitationModel();
            $citation->raw = $rowRaw;
            $citations[] = (array)$citation;
        }

        return $citations;
    }
}
