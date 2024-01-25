<?php
/**
 * @file plugins/generic/optimetaCitations/classes/Orcid/Enrich.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Enrich
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Enrich class for Orcid
 */

namespace APP\plugins\generic\optimetaCitations\classes\Orcid;

use APP\plugins\generic\optimetaCitations\classes\DataModels\CitationModel;
use APP\plugins\generic\optimetaCitations\classes\Orcid\DataModels\Author;
use APP\plugins\generic\optimetaCitations\classes\PID\Orcid;
use APP\plugins\generic\optimetaCitations\OptimetaCitationsPlugin;

class Enrich
{
    /**
     * @var OptimetaCitationsPlugin
     */
    public OptimetaCitationsPlugin $plugin;

    /**
     * @var Api
     */
    public Api $api;

    public function __construct(OptimetaCitationsPlugin $plugin)
    {
        $this->plugin = $plugin;

        $this->api = new Api($this->plugin);
    }

    /**
     * Get all information from Orcid for the authors in this citation
     *
     * @param CitationModel $citation
     * @return CitationModel
     */
    public function getEnriched(CitationModel $citation): CitationModel
    {
        if (empty($citation->authors)) return $citation;

        $pidOrcid = new Orcid();

        for ($i = 0; $i < count($citation->authors); $i++) {
            if (!empty($citation->authors[$i]['orcid'])) {
                $orcid = $citation->authors[$i]['orcid'];
                $author = $this->getAuthor($pidOrcid->removePrefixFromUrl($orcid));

                // Check if not empty and ORCID Record is not deactivated
                if (!empty($author->given_name) &&
                    strtolower(trim($author->given_name)) !== 'given names deactivated')
                    $citation->authors[$i]['given_name'] = $author->given_name;

                // Check if not empty and ORCID Record is not deactivated
                if (!empty($author->family_name) &&
                    strtolower(trim($author->family_name)) !== 'family name deactivated')
                    $citation->authors[$i]['family_name'] = $author->family_name;

                if (empty($author->given_name) && empty($author->family_name))
                    $citation->authors[$i]['orcid'] = '';
            }
        }

        return $citation;
    }

    /**
     * This method retrieves the Author from the API
     *
     * @param string $orcid
     * @return Author
     */
    public function getAuthor(string $orcid): Author
    {
        $author = new Author();

        if (empty($orcid)) return $author;

        $orcidObject = $this->api->getObjectFromApi($orcid);

        if(empty($orcidObject)) return $author;

        $author->orcid = $orcid;

        if (!empty($orcidObject['person']['name']['given-names']['value']))
            $author->given_name = $orcidObject['person']['name']['given-names']['value'];

        if (!empty($orcidObject['person']['name']['family-name']['value']))
            $author->family_name = $orcidObject['person']['name']['family-name']['value'];

        return $author;
    }

}
