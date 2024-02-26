<?php
/**
 * @file classes/External/External/EnrichAbstract.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class EnrichAbstract
 * @brief Abstract enrich class to be extended by Enrich classes.
 */

namespace APP\plugins\generic\citationManager\classes\External;

use APP\plugins\generic\citationManager\CitationManagerPlugin;
use APP\plugins\generic\citationManager\classes\DataModels\Citation\CitationModel;

abstract class EnrichAbstract
{
    /** @var CitationManagerPlugin */
    public CitationManagerPlugin $plugin;

    /** @var ApiAbstract */
    public ApiAbstract $api;

    /**
     * Process this external service
     *
     * @param CitationModel $citation
     * @return CitationModel
     */
    public function execute(CitationModel $citation): CitationModel
    {
        return new CitationModel();
    }
}
