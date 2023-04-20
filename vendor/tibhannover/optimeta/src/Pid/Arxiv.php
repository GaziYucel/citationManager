<?php
namespace Optimeta\Shared\Pid;

class Arxiv
{
    /**
     * @desc Correct prefix
     * @var string
     */
    public string $prefix = 'https://arxiv.org/abs/';

    /**
     * @desc Incorrect prefixes
     * @var array|string[]
     */
    public array $prefixInCorrect = [
        'http://arxiv.org/abs/'
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
     * Add prefix to URL
     * @param string|null $url
     * @return string
     */
    public function addPrefixToUrl(?string $url): string
    {
        if(empty($url)) { return ''; }

        return $url . $this->prefix;
    }
}
