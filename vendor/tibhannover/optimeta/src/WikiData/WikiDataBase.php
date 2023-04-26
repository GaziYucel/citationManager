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
    protected Property $property;

    /**
     *  Optimeta\Shared\WikiData\Api
     * @var Api
     */
    protected Api $api;

    function __construct(bool $isTest = false, ?string $username = '', ?string $password = '')
    {
        $this->property = new Property($isTest);

        if ($isTest)
            $this->url = $this->urlTest;

        $this->api = new Api($this->url, $username, $password);
    }

    /**
     * Get entity with action query
     * @param string $doi
     * @param string $searchString
     * @return string
     */
    public function getEntity(string $doi, string $searchString): string
    {
        if (empty($doi) && empty($searchString)) return '';

        $qid = '';

        $action = 'query';
        $query = [
            "prop" => "",
            "list" => "search",
            "srsearch" => $doi . ' ' . $searchString,
            "srlimit" => "2"];

        $response = json_decode($this->api->actionGet($action, $query), true);

        if (empty($response)) return '';

        if (!empty($response['query']['search'][0]['title']))
            $qid = $response['query']['search'][0]['title'];

        if (strtolower($doi) == strtolower($this->getDoi($qid)))
            return $qid;

        return '';
    }

    /**
     * * Get doi with action get entities
     * @param string $qid
     * @return string
     */
    public function getDoi(string $qid): string
    {
        if (empty($qid)) return '';

        $action = 'wbgetentities';
        $query = ["ids" => $qid];

        $response = json_decode($this->api->actionGet($action, $query), true);

        $pid = $this->property->doi['pid'];

        if (empty($response)) return '';

        if (!empty($response['entities'][$qid]['claims'][$this->property->doi['pid']][0]['mainsnak']['datavalue']['value']))
            return $response['entities'][$qid]['claims'][$this->property->doi['pid']][0]['mainsnak']['datavalue']['value'];

        return '';
    }

    public function submitWork(array $work): string
    {
        $action = "wbeditentity";
        $query = [];
        $qid = $work["qid"];
        $data = [];

        if (!empty($qid)) {
            $query["id"] = $qid; // Q224871
        } else {
            $query["new"] = "item";
        }

        $article = new Article();

        $labels = $article->getLabelAsJson($work["locale"], $work["label"]);

        $claims = [];

        if (!empty($work["claims"]["doi"])) {
            $claims[] = $article->getDefaultClaimAsJson(
                $this->property->doi['pid'],
                $work["claims"]["doi"]);
        }

        if (!empty($work["claims"]["publicationDate"])) {
            $claims[] = $article->getPointInTimeClaimAsJson(
                $this->property->publicationDate['pid'],
                $work["claims"]["publicationDate"]);
        }

        $data["labels"] = $labels;
        if (!empty($claims))
            $data["claims"] = $claims;

        $form["data"] = json_encode($data);

        $this->log .= '<form>' . json_encode($form, JSON_UNESCAPED_SLASHES) . '</form>';

        $response = $this->api->actionPost($action, $query, $form);

        $responseArray = json_decode($response, true);

        if (!empty($responseArray["entity"]["id"])) {
            $qid = $responseArray["entity"]["id"];
        }

        $this->log .= '<response>' . $response . '</response>';

        return $qid;
    }

    function __destruct()
    {
        error_log('WikiDataBase->__destruct: ' . $this->log);
    }
}
