<?php
/**
 * @file plugins/generic/citationManager/classes/Wikidata/DataModels/CitedArticle.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class CitedArticle
 * @ingroup plugins_generic_citationmanager
 *
 * @brief Articles are scholarly documents like journal articles, books, datasets, and theses.
 */

namespace APP\plugins\generic\citationManager\classes\Wikidata\DataModels;

use APP\plugins\generic\citationManager\classes\DataModels\CitationModel;
use APP\plugins\generic\citationManager\classes\Helpers\ArrayHelper;
use APP\plugins\generic\citationManager\classes\Wikidata\Api;

class CitedArticle
{
    /**
     * @var Api
     */
    public Api $api;

    /**
     * @var Property
     */
    public Property $property;

    /**
     * @param Api $api
     * @param Property $property
     */
    public function __construct(Api $api, Property $property)
    {
        $this->api = $api;
        $this->property = $property;
    }

    /**
     * Create item and return QID
     *
     * @param string $locale
     * @param CitationModel $citation
     *
     * @return string
     */
    public function process(string $locale, CitationModel $citation): string
    {
        if (empty($locale) || empty($citation->doi)) return '';

        $pid = $citation->doi;
        $label = $citation->title;

        $search = $this->api->search($pid);

        // nothing found
        if (empty($search)) return '';

        // found, match and return qid if matched
        $qid = $this->api->match($this->property->doi['id'], $pid, $search);
        if (!empty($qid)) return $qid;

        // not found, create and return qid
        $claim = new Claim();

        $data['labels'] = $claim->getLabels($locale, $label);

        $data['claims'] = [
            $claim->getInstanceOf(
                $this->property->instanceOfScientificArticle['id'],
                $this->property->instanceOfScientificArticle['default'],
                str_replace('Q','', $this->property->instanceOfScientificArticle['default'])),
            $claim->getExternalId(
                $this->property->doi['id'],
                $pid),
            $claim->getMonoLingualText(
                $this->property->title['id'],
                $label,
                $locale)
        ];

        return $this->api->add($data);
    }
}
