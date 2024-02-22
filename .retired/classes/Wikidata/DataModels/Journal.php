<?php
/**
 * @file plugins/generic/citationManager/classes/Wikidata/DataModels/Journal.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Journal
 * @ingroup plugins_generic_citationmanager
 *
 * @brief Journal are periodical journal publishing scientific research.
 */

namespace APP\plugins\generic\citationManager\classes\Wikidata\DataModels;

use APP\plugins\generic\citationManager\classes\Helpers\ArrayHelper;
use APP\plugins\generic\citationManager\classes\Wikidata\Api;
use PKP\context\Context;

class Journal
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
     * @param Context $context
     *
     * @return string
     */
    public function process(string $locale, Context $context): string
    {
        if (empty($locale)) return '';

        $pid = $context->getData('onlineIssn');
        $label = $context->getData('name')[$locale];

        if (empty($pid) || empty($label)) return '';

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
                $this->property->instanceOfScientificJournal['id'],
                $this->property->instanceOfScientificJournal['default'],
                str_replace('Q', '', $this->property->instanceOfScientificJournal['default'])),
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
