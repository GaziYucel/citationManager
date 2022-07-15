<?php
namespace Optimeta\Citations\Pid;

class Url
{
    /**
     * @desc Regex to extract URL
     * @var string
     */
    public $regex = '%\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))%s';

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

        $match = trim($match, '.');

        return $match;
    }
}