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

use APP\plugins\generic\optimetaCitations\classes\Helpers\LogHelper;
use APP\plugins\generic\optimetaCitations\classes\PID\Wikidata;
use APP\plugins\generic\optimetaCitations\classes\Wikidata\DataModels\Property;
use APP\plugins\generic\optimetaCitations\classes\DataModels\CitationModel;
use APP\plugins\generic\optimetaCitations\classes\PID\Doi;
use APP\plugins\generic\optimetaCitations\OptimetaCitationsPlugin;

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

        $username = $this->plugin->getSetting($this->plugin->getCurrentContextId(),
            OptimetaCitationsPlugin::OPTIMETA_CITATIONS_WIKIDATA_USERNAME);

        $password = $this->plugin->getSetting($this->plugin->getCurrentContextId(),
            OptimetaCitationsPlugin::OPTIMETA_CITATIONS_WIKIDATA_PASSWORD);

        $this->api = new Api($username, $password);

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

//        error_log('Wikidata::Enrich::getEnriched: ' . $doi);

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
        error_log('Wikidata::Enrich::getQid: ' . $doi);

        if (empty($doi)) return '';

        $action = 'query';
        $query = [
            "prop" => "",
            "list" => "search",
            "srsearch" => $doi,
            "srlimit" => "2"];

        $response = $this->api->actionGet($action, $query);
        if (empty($response) || empty($response['query']['search'])) return '';

        $qids = [];
        foreach ($response['query']['search'] as $index => $item) {
            if (!empty($item['title'])) $qids[] = $item['title'];
        }

        return $this->checkAndReturnCorrectQid($qids, $doi);
    }

    /**
     * Check received qid's with doi and return correct qid
     *
     * @param array $qids
     * @param string $doi
     * @return string
     */
    private function checkAndReturnCorrectQid(array $qids, string $doi): string
    {
        if (empty($qids) || empty($doi)) return false;

        $action = 'wbgetentities';
        $query = ["ids" => implode('|', $qids)];

        $response = $this->api->actionGet($action, $query);

        if (empty($response) || empty($response['entities'])) return '';

        foreach ($response['entities'] as $qid => $item) {
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
