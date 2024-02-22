<?php
/**
 * @file plugins/generic/citationManager/Wikidata/DataModels/Author.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Author
 * @ingroup plugins_generic_citationmanager
 *
 * @brief Authors are people who create works.
 */

namespace APP\plugins\generic\citationManager\classes\Wikidata\DataModels;

use APP\plugins\generic\citationManager\classes\DataModels\AuthorModel;
use APP\plugins\generic\citationManager\classes\Helpers\ArrayHelper;
use APP\plugins\generic\citationManager\classes\Wikidata\Api;

class Author
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
     * @param string $pid
     * @param string $label
     *
     * @return string
     */
    public function process(string $locale, string $pid, string $label): string
    {
        if (empty($locale) || empty($pid) || empty($label)) return '';

        $search = $this->api->search($pid);

        // nothing found
        if (empty($search)) return '';

        // found, match and return qid if matched
        $qid = $this->api->match($this->property->orcidId['id'], $pid, $search);
        if (!empty($qid)) return $qid;

        // not found, create and return qid
        $claim = new Claim();

        $data['labels'] = $claim->getLabels($locale, $label);

        $data['claims'] = [
            $claim->getInstanceOf(
                $this->property->instanceOfHuman['id'],
                $this->property->instanceOfHuman['default'],
                str_replace('Q','', $this->property->instanceOfHuman['default'])
            ),
            $claim->getExternalId(
                $this->property->orcidId['id'],
                $pid)
        ];

        return $this->api->add($data);
    }
}
