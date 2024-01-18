<?php
/**
 * @file plugins/generic/optimetaCitations/classes/Wikidata/Enrich.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Wikidata
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Wikidata class for Wikidata
 */

namespace APP\plugins\generic\optimetaCitations\classes\Wikidata;

use APP\plugins\generic\optimetaCitations\classes\PID\Wikidata;
use APP\plugins\generic\optimetaCitations\classes\Wikidata\Model\Property;
use APP\plugins\generic\optimetaCitations\OptimetaCitationsPlugin;
use APP\plugins\generic\optimetaCitations\classes\Model\CitationModel;
use APP\plugins\generic\optimetaCitations\classes\PID\Doi;

class Enrich
{
    /**
     * @var OptimetaCitationsPlugin
     */
    public OptimetaCitationsPlugin $plugin;

    /**
     * @var Api
     */
    public Api $api;

    /**
     * @var bool
     */
    protected bool $isProduction = false;

    /**
     * Property
     * @var Property
     */
    private Property $property;

    public function __construct(OptimetaCitationsPlugin $plugin)
    {
        $this->plugin = $plugin;

        $this->api = new Api($this->plugin);

        $isProduction = false;
        if ($this->plugin->getSetting(
            $this->plugin->getCurrentContextId(),
            OptimetaCitationsPlugin::OPTIMETA_CITATIONS_IS_PRODUCTION_KEY) === 'true') {
            $isProduction = true;
        }

        $this->property = new Property($isProduction);
    }

    /**
     * Get information from Wikidata and return as CitationModel
     *
     * @param CitationModel $citation
     * @return CitationModel
     */
    public function getEnriched(CitationModel $citation): CitationModel
    {
        $objDoi = new Doi();
        $doi = $objDoi->removePrefixFromUrl($citation->doi);

        $citation->wikidata_qid = $this->getQid($doi);

        $objWikidata = new Wikidata();
        $citation->wikidata_url = $objWikidata->addPrefixToPid($citation->wikidata_qid);

        return $citation;
    }

    /**
     * Get entity with action query
     *
     * @param string $doi
     * @return string
     */
    public function getQid(string $doi): string
    {
        if (empty($doi)) return '';

        $action = 'query';
        $query = [
            "prop" => "",
            "list" => "search",
            "srsearch" => $doi,
            "srlimit" => "2"];

        $response = json_decode($this->api->actionGet($action, $query), true);

        if (empty($response) || $response['query']['search']) return '';


        $qids = [];
        foreach($response['query']['search'] as $index => $item) {
            if (!empty($item['title'])) $qids[] = $item['title'];
        }

        return $this->checkAndReturnCorrectQid($qids, $doi);
    }

    private function checkAndReturnCorrectQid(array $qids, string $doi): string
    {
        if (empty($qids) || empty($doi)) return false;

        $action = 'wbgetentities';
        $query = ["ids" => implode('|', $qids)];

        $response = json_decode($this->api->actionGet($action, $query), true);

        if (empty($response) || empty($response['entities'])) return '';

        foreach($response['entities'] as $qid => $item) {
            if (strtolower($doi) === strtolower($this->getDoiFromItem($item))) return $qid;
        }

        return '';
    }

    /**
     * Get doi from item
     *
     * @param array $item
     * @return string
     */
    private function getDoiFromItem(array $item): string
    {
        if (!empty($item['claims'][$this->property->doi['pid']][0]['mainsnak']['datavalue']['value'])) {
            return $item['claims'][$this->property->doi['pid']][0]['mainsnak']['datavalue']['value'];
        }

        return '';
    }
}
