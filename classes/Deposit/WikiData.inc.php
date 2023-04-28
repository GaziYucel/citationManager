<?php
/**
 * @file plugins/generic/optimetaCitations/classes/Deposit/WikiData.inc.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class WikiData
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Depositor class WikiData
 */

namespace Optimeta\Citations\Deposit;

use Journal;
use Optimeta\Shared\WikiData\WikiDataBase;
use OptimetaCitationsPlugin;
use Publication;

class WikiData
{
    /**
     * Is this instance production
     * @var bool
     */
    protected bool $isProduction = false;

    /**
     * Log string
     * @var string
     */
    public string $log = '';

    /**
     * Instance of OptimetaCitationsPlugin
     * @var object OptimetaCitationsPlugin
     */
    protected object $plugin;

    public function __construct()
    {
        $this->plugin = new OptimetaCitationsPlugin();
        if ($this->plugin->getSetting($this->plugin->getCurrentContextId(),
                OPTIMETA_CITATIONS_IS_PRODUCTION_KEY) === 'true') {
            $this->isProduction = true;
        }
    }

    /**
     * Submits work to WikiData
     * @param Journal $context
     * @param object|null $issue
     * @param object $submission
     * @param Publication $publication
     * @param array $authors
     * @param array $publicationWork
     * @param array $citations
     * @return string
     */
    public function submitWork(
        Journal     $context,
        ?object     $issue,
        object      $submission,
        Publication $publication,
        array       $authors,
        array       $publicationWork,
        array       $citations): string
    {
        $username = $this->plugin->getSetting($context->getId(),
            OPTIMETA_CITATIONS_WIKIDATA_USERNAME);
        $password = $this->plugin->getSetting($context->getId(),
            OPTIMETA_CITATIONS_WIKIDATA_PASSWORD);

        // return '' url not empty or username and password empty
        if (empty($username) || empty($password))
            return '';

        $doi = $submission->getStoredPubId('doi');
        $locale = $publication->getData('locale');
        $label = $publication->getData('title', $locale);

        $wikiDataBase = new WikiDataBase($this->isProduction, $username, $password);

        $qid = $wikiDataBase->getQidWithDoi($doi);

        // check if article (item) exists; create if not
        if (empty($qid)) {
            $qid = $wikiDataBase->createItem($doi, $locale, $label);
            $this->log .= '<qid action="created">' . $qid . '</qid>';
        }

        // publication date
        $publicationDate = date('+Y-m-d\T00:00:00\Z', strtotime($issue->getData('datePublished')));
        $wikiDataBase->createClaimPublicationDate($qid, $publicationDate);


        return $qid;
    }

    function __destruct()
    {
        // error_log('WikiData->__destruct: ' . $this->log);
    }
}
