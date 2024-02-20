<?php
/**
 * @file classes/External/Wikidata/Enrich.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Wikidata
 * @brief Wikidata class for Wikidata
 */

namespace APP\plugins\generic\citationManager\classes\External\Wikidata;

use APP\plugins\generic\citationManager\CitationManagerPlugin;
use APP\plugins\generic\citationManager\classes\DataModels\Citation\CitationModel;
use APP\plugins\generic\citationManager\classes\External\EnrichAbstract;
use APP\plugins\generic\citationManager\classes\External\Wikidata\DataModels\Property;

class Enrich extends EnrichAbstract
{
    /** @var Property */
    public Property $property;

    /** @param CitationManagerPlugin $plugin */
    public function __construct(CitationManagerPlugin $plugin)
    {
        $this->plugin = $plugin;
        $this->api = new Api($plugin);
        $this->property = new Property();
    }

    /**
     * Get information from Wikidata and return as CitationModel
     *
     * @param CitationModel $citation
     * @return CitationModel
     */
    public function execute(CitationModel $citation): CitationModel
    {
        if (empty($citation->doi)) return $citation;

        // find qid and return qid if found
        $qid = $this->api
            ->getQidFromItem($this->api
                ->getItemWithPropertyAndPid(
                    $this->property->doi['id'], $citation->doi));

        if (!empty($qid))
            $citation->wikidata_id = $qid;

        return $citation;
    }
}
