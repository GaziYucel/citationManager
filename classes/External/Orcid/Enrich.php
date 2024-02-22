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
use APP\plugins\generic\citationManager\classes\External\EnrichAbstract;
use APP\plugins\generic\citationManager\classes\External\Orcid\DataModels\Mappings;
use APP\plugins\generic\citationManager\classes\Helpers\ArrayHelper;
use APP\plugins\generic\citationManager\classes\PID\Orcid;

class Enrich extends EnrichAbstract
{
    /** @param CitationManagerPlugin $plugin */
    public function __construct(CitationManagerPlugin $plugin)
    {
        $this->plugin = $plugin;
        $this->api = new Api($plugin);
    }

    /**
     * Get all information from Orcid for the authors in this citation
     *
     * @param CitationModel $citation
     * @return CitationModel
     */
    public function execute(CitationModel $citation): CitationModel
    {
        if (empty($citation->authors)) return $citation;

        $count = count($citation->authors);
        for ($i = 0; $i < $count; $i++) {

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
