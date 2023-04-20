<?php
namespace Optimeta\Shared\Pid;

class Ror
{
    /**
     * @desc Regex to extract URL
     * @var string
     */
    public string $regex = '/\[[\s]*https:\/\/ror\.org\/[\w|\d]*[\s]*\]/';

    /**
     * @desc Correct prefix
     * @var string
     */
    public string $prefix = 'https://ror.org/';

    /**
     * @desc Incorrect prefixes
     * @var array|string[]
     */
    public array $prefixInCorrect = [
        'http://ror.org/'
    ];

    /**
     * @desc Remove $prefix from URL
     * @param string|null $url
     * @return string
     */
    public function removePrefixFromUrl(?string $url): string
    {
        if(empty($url)) { return ''; }

        return str_replace($this->prefix, '', $url);
    }

    /**
     * Add $prefix to URL
     * @param string|null $url
     * @return string
     */
    public function addPrefixToUrl(?string $url): string
    {
        if(empty($url)) { return ''; }

        return $url . $this->prefix;
    }
}
