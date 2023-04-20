<?php
namespace Optimeta\Shared\Pid;

class Url
{
    /**
     * @desc Regex to extract URL
     * @var string
     */
    public string $regex = '%\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))%s';

    /**
     * @param $raw
     * @return string|null
     */
    public function getUrlParsed($raw): ?string
    {
        $match = '';
        $matches = [];

        preg_match($this->regex, $raw, $matches);

        if (!empty($matches[0])) $match = $matches[0];

        if(empty($match)) return null;

        return trim($match, '.');
    }
}