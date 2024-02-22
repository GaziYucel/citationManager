<?php
/**
 * @file classes/Handlers/PidExtractorHandler.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yücel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class PidExtractorHandler
 * @ingroup plugins_generic_citationmanager
 *
 * @brief PidExtractorHandler class for extracting PID's, e.g. DOI, Handle, ...
 */

namespace APP\plugins\generic\citationManager\classes\Handlers;

use APP\plugins\generic\citationManager\classes\DataModels\CitationModel;
use APP\plugins\generic\citationManager\classes\Helpers\ClassHelper;
use APP\plugins\generic\citationManager\classes\Helpers\LogHelper;
use APP\plugins\generic\citationManager\classes\Helpers\StringHelper;
use APP\plugins\generic\citationManager\classes\PID\Arxiv;
use APP\plugins\generic\citationManager\classes\PID\Doi;
use APP\plugins\generic\citationManager\classes\PID\Handle;
use APP\plugins\generic\citationManager\classes\PID\Url;
use APP\plugins\generic\citationManager\classes\PID\Urn;
use APP\plugins\generic\citationManager\CitationManagerPlugin;

class PidExtractorHandler
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
     * Parse and structure citations
     *
     * @param array $citationsIn
     *
     * @return array
     */
    public function execute(array $citationsIn): array
    {
        if (empty($citationsIn)) return [];

        $citations = [];

        // loop through citations and parse every citation
        foreach ($citationsIn as $row) {
            /* @var CitationModel $citation */
            $citation = ClassHelper::getClassWithValuesAssigned(new CitationModel(), $row);

            $rowRaw = $citation->raw;

            $rowRaw = StringHelper::trim($rowRaw, ' .,');
            $rowRaw = StringHelper::stripSlashes($rowRaw);
            $rowRaw = StringHelper::normalizeWhiteSpace($rowRaw);
            $rowRaw = StringHelper::removeNumberPrefixFromString($rowRaw);

            // doi
            $pidDoi = new Doi();
            $citation->doi = $pidDoi->extractFromString($rowRaw);

            // remove doi
            $rowRaw = str_replace(
                $pidDoi->addPrefix($citation->doi),
                '',
                $pidDoi->normalize($rowRaw, $citation->doi));

            // parse url (after parsing doi)
            $pidUrl = new Url();
            $citation->url = $pidUrl->extractFromString($rowRaw);

            // handle
            $pidHandle = new Handle(); //todo: add pid to citation/work model
            $citation->url = str_replace($pidHandle->prefixInCorrect, $pidHandle->prefix, $citation->url);

            // arxiv
            $pidArxiv = new Arxiv(); //todo: add pid to citation/work model
            $citation->url = str_replace($pidArxiv->prefixInCorrect, $pidArxiv->prefix, $citation->url);

            // urn
            $objUrn = new Urn();
            $citation->urn = $objUrn->extractFromString($rowRaw);

            // push to citations parsed array
            $citations[] = (array)$citation;
        }

        return $citations;
    }
}
