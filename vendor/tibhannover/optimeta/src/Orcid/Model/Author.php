<?php
/**
 * @desc Authors are people who create works.
 */
namespace Optimeta\Shared\Orcid\Model;

class Author
{
    /**
     * @var string
     * @desc The ORCID ID for this author. ORCID global and unique ID for authors.
     * @see
     * @example "https://orcid.org/0000-0001-6187-6610"
     */
    public $orcid;

    /**
     * @var string
     * @desc The given name of the author as a single string.
     * @see
     * @example "Jason"
     */
    public $given_name;

    /**
     * @var string
     * @desc The family name of the author as a single string.
     * @see
     * @example "Priem"
     */
    public $family_name;
}
