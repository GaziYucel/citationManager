<?php
namespace Optimeta\Citations\Pid;

class Urn
{
    /**
     * @desc Regex to extract URN
     * @var string
     */
    public $regex = '/urn:([a-z0-9][a-z0-9-]{1,31}):((?:[-a-z0-9()+,.:=@;$_!*\'&~\/]|%[0-9a-f]{2})+)(?:(\?\+)((?:(?!\?=)(?:[-a-z0-9()+,.:=@;$_!*\'&~\/\?]|%[0-9a-f]{2}))*))?(?:(\?=)((?:(?!#).)*))?(?:(#)((?:[-a-z0-9()+,.:=@;$_!*\'&~\/\?]|%[0-9a-f]{2})*))?$/i';

    /**
     * @param $raw
     * @return string|null
     */
    public function getUrnParsed($raw): ?string
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