<?php
/**
 * @file classes/External/OpenAlex/Enrich.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class OpenAlex
 * @brief OpenAlex class for OpenAlex
 */

namespace APP\plugins\generic\citationManager\classes\External\OpenAlex;

use APP\plugins\generic\citationManager\CitationManagerPlugin;
use APP\plugins\generic\citationManager\classes\DataModels\Citation\AuthorModel;
use APP\plugins\generic\citationManager\classes\DataModels\Citation\CitationModel;
use APP\plugins\generic\citationManager\classes\External\EnrichAbstract;
use APP\plugins\generic\citationManager\classes\External\OpenAlex\DataModels\Mappings;
use APP\plugins\generic\citationManager\classes\Helpers\ArrayHelper;
use APP\plugins\generic\citationManager\classes\PID\Doi;
use APP\plugins\generic\citationManager\classes\PID\OpenAlex;
use APP\plugins\generic\citationManager\classes\PID\Orcid;

class Enrich extends EnrichAbstract
{
    /**
     * Constructor
     *
     * @param CitationManagerPlugin $plugin
     */
    public function __construct(CitationManagerPlugin $plugin)
    {
        $this->plugin = $plugin;
        $this->api = new Api($plugin);
    }

    /**
     * Get all information from OpenAlex and return as CitationModel
     *
     * @param CitationModel $citation
     * @return CitationModel
     */
    public function execute(CitationModel $citation): CitationModel
    {
        $openAlexArray = $this->api->getWork(Doi::removePrefix($citation->doi));

        if (empty($openAlexArray)) return $citation;

        foreach (Mappings::getWork() as $key => $value) {
            switch ($key) {
                case 'authors':
                    foreach ($openAlexArray['authorships'] as $index => $authorship) {
                        $citation->authors[] = $this->getAuthor($authorship);
                    }
                    break;
                default:
                    if (is_array($value)) {
                        $citation->$key =
                            ArrayHelper::getValue($openAlexArray, $value);
                    } else {
                        $citation->$key = $openAlexArray[$value];
                    }
                    break;
            }
        }

        if (!empty($citation->openalex_id)) $citation->isProcessed = true;

        $citation->openalex_id = OpenAlex::removePrefix($citation->openalex_id);

        return $citation;
    }

    /**
     * Convert to AuthorModel with mappings
     *
     * @param array $authorIn Input values
     * @return AuthorModel
     */
    private function getAuthor(array $authorIn): AuthorModel
    {
        $authorOut = new AuthorModel();
        $mappings = Mappings::getAuthor();

        foreach ($mappings as $key => $val) {
            if (is_array($val)) {
                $authorOut->$key = ArrayHelper::getValue($authorIn, $val);
            } else {
                $authorOut->$key = $authorIn[$key];
            }
        }

        $authorOut->orcid_id = Orcid::removePrefix($authorOut->orcid_id);
        $authorOut->openalex_id = OpenAlex::removePrefix($authorOut->openalex_id);

        return $authorOut;
    }
}
