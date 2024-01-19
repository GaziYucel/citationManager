<?php
/**
 * @file plugins/generic/optimetaCitations/classes/Orcid/Model/Author.php
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

namespace APP\plugins\generic\optimetaCitations\classes\Orcid\Model;

class Author
{
    /**
     * The ORCID ID for this author. ORCID global and unique ID for authors.
     *
     * @var string
     * @see
     * @example "https://orcid.org/0000-0001-6187-6610"
     */
    public string $orcid;

    /**
     * The given name of the author as a single string.
     *
     * @var string
     * @see
     * @example "John"
     */
    public string $given_name;

    /**
     * The family name of the author as a single string.
     *
     * @var string
     * @see
     * @example "Doe"
     */
    public string $family_name;
}
