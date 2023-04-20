<?php
namespace Optimeta\Shared\Pid;

class Orcid
{
    /**
     * @desc Correct prefix
     * @var string
     */
    public $prefix = 'https://orcid.org/';

    /**
     * @desc Incorrect prefixes
     * @var array|string[]
     */
    public array $prefixInCorrect = [
        'http://orcid.org/'
    ];

    /**
     * @desc Remove https://orcid.org/ from ORCID URL
     * @param string|null $url
     * @return string
     */
    public function removePrefixFromUrl(?string $url): string
    {
        if(empty($url)) { return ''; }

        return str_replace($this->prefix, '', $url);
    }
}