<?php
/**
 * @file plugins/generic/optimetaCitations/classes/Enrich/Orcid.inc.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Orcid
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Orcid class for Orcid
 */

namespace Optimeta\Citations\Enrich;

use Optimeta\Citations\Model\CitationModel;
use Optimeta\Shared\Orcid\OrcidBase;

class Orcid
{
    /**
     * Get all information from Orcid for the authors in this citation
     * @param CitationModel $citation
     * @return CitationModel
     */
    public function getAuthors(CitationModel $citation): CitationModel
    {
        if (empty($citation->authors)) return $citation;

        $objOrcid = new \Optimeta\Shared\Pid\Orcid();
        $ojbOrcidBase = new OrcidBase();

        for ($i = 0; $i < count($citation->authors); $i++) {
            if (!empty($citation->authors[$i]['orcid'])) {
                $orcid = $citation->authors[$i]['orcid'];
                $author = $ojbOrcidBase->getAuthorFromApi($objOrcid->removePrefixFromUrl($orcid));

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
}