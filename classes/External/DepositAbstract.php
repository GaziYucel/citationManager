<?php
/**
 * @file classes/External/DepositAbstract.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class DepositAbstract
 * @brief Abstract deposit class to deposit to external services
 */

namespace APP\plugins\generic\citationManager\classes\External;

use APP\plugins\generic\citationManager\CitationManagerPlugin;
use APP\plugins\generic\citationManager\classes\DataModels\Metadata\MetadataPublication;
use Issue;
use Journal;
use Publication;
use Submission;

abstract class DepositAbstract
{
    /** @var CitationManagerPlugin */
    protected CitationManagerPlugin $plugin;

    /** @var ApiAbstract */
    public ApiAbstract $api;

    /**
     * Executes deposits to external services
     *
     * @param Journal $context
     * @param Issue $issue
     * @param Submission $submission
     * @param Publication $publication
     * @param MetadataPublication $publicationMetadata
     * @param array $citations
     * @return bool
     */
    public function execute(Journal             $context,
                            Issue               $issue,
                            Submission          $submission,
                            Publication         $publication,
                            MetadataPublication $publicationMetadata,
                            array               $citations): bool
    {
        return true;
    }
}
