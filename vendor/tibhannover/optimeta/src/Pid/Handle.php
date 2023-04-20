<?php
namespace Optimeta\Shared\Pid;

class Handle
{
    /**
     * @desc Correct prefix
     * @var string
     */
    public string $prefix = 'https://hdl.handle.net/';

    /**
     * @desc Incorrect prefixes
     * @var array|string[]
     */
    public array $prefixInCorrect = [
        'http://hdl.handle.net/'
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
