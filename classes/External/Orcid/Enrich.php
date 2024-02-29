<?php
/**
 * @file classes/External/Orcid/Enrich.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Enrich
 * @brief Enrich class for Orcid
 */

namespace APP\plugins\generic\citationManager\classes\External\Orcid;

use APP\plugins\generic\citationManager\CitationManagerPlugin;
use APP\plugins\generic\citationManager\classes\DataModels\Citation\AuthorModel;
use APP\plugins\generic\citationManager\classes\DataModels\Citation\CitationModel;
use APP\plugins\generic\citationManager\classes\DataModels\Metadata\MetadataJournal;
use APP\plugins\generic\citationManager\classes\DataModels\Metadata\MetadataPublication;
use APP\plugins\generic\citationManager\classes\External\EnrichAbstract;
use APP\plugins\generic\citationManager\classes\External\Orcid\DataModels\Mappings;
use APP\plugins\generic\citationManager\classes\Helpers\ArrayHelper;
use APP\plugins\generic\citationManager\classes\PID\Orcid;
use Context;
use Issue;
use Publication;
use Submission;

class Enrich extends EnrichAbstract
{
    /** @copydoc EnrichAbstract::__construct */
    public function __construct(CitationManagerPlugin $plugin,
                                ?Context              $journal,
                                ?Issue                $issue,
                                ?Submission           $submission,
                                ?Publication          $publication,
                                ?MetadataJournal      $metadataJournal,
                                ?MetadataPublication  $metadataPublication,
                                ?array                $authors,
                                ?array                $citations)
    {
        parent::__construct($plugin, $journal, $issue, $submission, $publication,
            $metadataJournal, $metadataPublication, $authors, $citations);
        $this->api = new Api($plugin);
    }

    /**
     * Process this external service
     *
     * @return bool
     */
    public function execute(): bool
    {
        $countCitations = count($this->citations);
        for ($i = 0; $i < $countCitations; $i++) {
            $this->citations[$i] = $this->processCitation($this->citations[$i]);
        }

        return true;
    }

    public function processCitation(CitationModel $citation): CitationModel
    {
        $countAuthors = count($citation->authors);

        for ($i = 0; $i < $countAuthors; $i++) {
            /* @var AuthorModel $author */
            $author = $citation->authors[$i];

            if (!empty($author->orcid_id)) {

                $person = $this->api->getPerson(Orcid::removePrefix($author->orcid_id));

                if (empty($person)) continue;

                foreach (Mappings::getAuthor() as $key => $value) {
                    if (is_array($value)) {
                        $author->$key = ArrayHelper::getValue($person, $value);
                    } else {
                        $author->$key = $person[$value];
                    }

                    if (str_contains(strtolower($author->$key), 'deactivated'))
                        $author->$key = '';
                }

                if (empty($author->given_name) && empty($author->family_name))
                    $author->orcid_id = '';
            }

            $citation->authors[$i] = $author;
        }

        return $citation;
    }
}
