<?php
namespace Optimeta\Citations\Pid;

class Arxiv
{
    /**
     * @desc Correct prefix
     * @var string
     */
    public $prefix = 'https://arxiv.org/abs/';

    /**
     * @desc Incorrect prefixes
     * @var string[]
     */
    public $prefixInCorrect = [
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
}
