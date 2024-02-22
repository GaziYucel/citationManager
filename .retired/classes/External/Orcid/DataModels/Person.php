<?php
/**
 * @file classes/External/Orcid/DataModels/Person.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Person
 * @brief Person
 */

namespace APP\plugins\generic\citationManager\classes\External\Orcid\DataModels;

class Person
{
    /** @var ?string The ORCID ID, e.g. https://orcid.org/0000-0001-6187-6610 */
    public ?string $orcid = null;

    /** @var string|null The given name of the author as a single string. */
    public ?string $given_name = null;

    /** @var string|null The family name of the author as a single string. */
    public ?string $family_name = null;
}
