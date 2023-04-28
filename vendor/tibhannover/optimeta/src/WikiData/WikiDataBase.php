<?php
/**
 * @file plugins/generic/optimetaCitations/vendor/tibhannover/optimeta/src/WikiData/WikiDataBase.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class WikiDataBase
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief WikiDataBase class
 */

namespace Optimeta\Shared\WikiData;

use Optimeta\Shared\WikiData\Model\Article;
use Optimeta\Shared\WikiData\Model\Property;

class WikiDataBase
{
    /**
     * Log string
     * @var string
     */
    public string $log = '';

    /**
     * Url for production
     * @var string
     */
    public string $url = 'https://www.wikidata.org/w/api.php';

    /**
     * Url for test
     * @var string
     */
    public string $urlTest = 'https://test.wikidata.org/w/api.php';

    /**
     * Optimeta\Shared\WikiData\Model\Property
     * @var Property
     */
    private Property $property;

    /**
     *  Optimeta\Shared\WikiData\Api
     * @var Api
     */
    private Api $api;

    /**
     * Current WikiData item object
     * @var array
     */
    private array $item;

    /**
     * Current WikiData QID
     * @var string
     */
    private string $qid;

    function __construct(bool $isProduction = false, ?string $username = '', ?string $password = '')
    {
        $this->property = new Property($isProduction);

        if (!$isProduction) $this->url = $this->urlTest;

        $this->api = new Api($this->url, $username, $password);
    }

    /**
     * Get entity with action query
     * @param string $doi
     * @param string $searchString
     * @return string $qid
     */
    public function getQidWithDoi(string $doi): string
    {
        if (empty($doi)) return '';

        $action = 'query';
        $query = [
            "prop" => "",
            "list" => "search",
            "srsearch" => $doi,
            "srlimit" => "2"];

        $response = json_decode($this->api->actionGet($action, $query), true);
        if (empty($response)) return '';

        if (!empty($response['query']['search'][0]['title'])) {
            $qid = $response['query']['search'][0]['title'];

            // set current wikidata entity we are working with
            $this->setItemAndQid($qid);

            // check if the doi retrieved belongs to this qid
            if (strtolower($doi) === strtolower($this->getDoi())) return $qid;
        }

        return '';
    }

    /**
     * Create WikiData item and return QID
     * @param string $doi
     * @param string $locale
     * @param string $label
     * @return string
     */
    public function createItem(string $doi, string $locale, string $label): string
    {
        if (empty($doi) || empty($locale) || empty($label))
            return '';

        $qid = '';
        $action = "wbeditentity";
        $query = [];
        $data = [];
        $claims = [];
        $article = new Article();

        $query["new"] = "item";
        $labels = $article->getLabelAsJson($locale, $label);
        $claims[] = $article->getDefaultClaimAsJson($this->property->doi['pid'], $doi);

        $data["labels"] = $labels;
        $data["claims"] = $claims;
        $form["data"] = json_encode($data);

        $response = json_decode($this->api->actionPost($action, $query, $form), true);

        // $this->log .= '<form>' . json_encode($form, JSON_UNESCAPED_SLASHES) . '</form>';
        // $this->log .= '<response>' . json_encode($response, JSON_UNESCAPED_SLASHES) . '</response>';

        if (!empty($response["entity"]["id"])) {
            $this->setItemAndQid($response["entity"]["id"]);
            return $response["entity"]["id"];
        }

        return '';
    }

    /**
     * Create claim publication date
     * @param string $qid
     * @param string $value
     * @return bool
     */
    public function createClaimPublicationDate(string $qid, string $value): bool
    {
        if (empty($qid) || empty($value)) return false;

        if (empty($this->item) || empty($this->qid)) {
            if(!$this->setItemAndQid($qid)) return false;
        }

        // add claim if empty
        if (empty($this->item['claims'][$this->property->publicationDate['pid']][0]['mainsnak']['datavalue']['value']['time'])) {
            $action = "wbeditentity";
            $query["id"] = $qid;
            $data = [];
            $claims = [];
            $article = new Article();

            $claims[] = $article->getPointInTimeClaimAsJson( $this->property->publicationDate['pid'], $value);

            $data["claims"] = $claims;

            $form["data"] = json_encode($data);

            $response = json_decode($this->api->actionPost($action, $query, $form), true);

//            $this->log .= '<form>' . json_encode($form, JSON_UNESCAPED_SLASHES) . '</form>';
//            $this->log .= '<response>' . json_encode($response, JSON_UNESCAPED_SLASHES) . '</response>';
        }

        return true;
    }

    /**
     * Get WikiData item and set item and qid
     * @param string $qid
     * @return bool
     */
    private function setItemAndQid(string $qid): bool
    {
        if (empty($qid)) return false;

        $action = 'wbgetentities';
        $query = ["ids" => $qid];

        $response = json_decode($this->api->actionGet($action, $query), true);

        if (!empty($response['entities'][$qid])) {
            $this->item = $response['entities'][$qid];
            $this->qid = $qid;

            // $this->log .= '<response>' . json_encode($response, JSON_UNESCAPED_SLASHES) . '</response>';
            // $this->log .= '<item>' . json_encode($this->item, JSON_UNESCAPED_SLASHES) . '</item>';

            return true;
        }

        return false;
    }

    /**
     * Get doi from this->item
     * @param string $qid
     * @return string
     */
    private function getDoi(): string
    {
        if (!empty($this->item['claims'][$this->property->doi['pid']][0]['mainsnak']['datavalue']['value']))
            return $this->item['claims'][$this->property->doi['pid']][0]['mainsnak']['datavalue']['value'];

        return '';
    }

    function __destruct()
    {
        // error_log('WikiDataBase->__destruct: ' . $this->log);
    }
}
