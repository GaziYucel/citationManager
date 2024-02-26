<?php
/**
 * @file classes/External/Crossref/Enrich.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Enrich
 * @brief Enrich class for Crossref
 */

namespace APP\plugins\generic\citationManager\classes\External\Crossref;

use APP\plugins\generic\citationManager\CitationManagerPlugin;
use APP\plugins\generic\citationManager\classes\DataModels\Citation\CitationModel;
use APP\plugins\generic\citationManager\classes\External\EnrichAbstract;

class Enrich extends EnrichAbstract
{
    /**
     * Constructor
     *
     * @param CitationManagerPlugin $plugin
     */
    public function __construct(CitationManagerPlugin $plugin)
    {
        $this->api = new Api($plugin);
    }

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
