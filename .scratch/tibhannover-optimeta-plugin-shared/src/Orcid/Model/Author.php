<?php
/**
 * @file plugins/generic/citationManager/vendor/tibhannover/optimeta/src/Orcid/Model/Author.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Author
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Authors are people who create works.
 */

namespace Optimeta\Shared\Orcid\Model;

class Author
{
    /**
     * @var string
     * The ORCID ID for this author. ORCID global and unique ID for authors.
     * @see
     * @example "https://orcid.org/0000-0001-6187-6610"
     */
    public $orcid;

    /**
     * @var string
     * The given name of the author as a single string.
     * @see
     * @example "Jason"
     */
    public $given_name;

    /**
     * @var string
     * The family name of the author as a single string.
     * @see
     * @example "Priem"
     */
    public $family_name;
}
