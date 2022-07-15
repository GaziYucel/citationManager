<?php
namespace Optimeta\Citations\Pid;

class Doi
{
    /**
     * @desc Regex to extract DOI
     * @var string
     */
    public $regex = '(10[.][0-9]{4,}[^\s"/<>]*/[^\s"<>]+)';

    /**
     * @desc Correct prefix
     * @var string
     */
    public $prefix = 'https://doi.org/';

    /**
     * @desc Incorrect prefixes
     * @var string[]
     */
    public $prefixInCorrect = [
        'http://doi.org/',
        'http://www.doi.org/',
        'https://www.doi.org/',
        'http://doi:',
        'https://doi:',
        'doi:',
        'doi: '
    ];

    /**
     * @param $raw
     * @return string|null
     */
    public function getDoiParsed($raw): ?string
    {
        $match = '';
        $matches = [];

        preg_match($this->regex, $raw, $matches);

        if (!empty($matches[0])) $match = $matches[0];

        if(empty($match)) return null;

        $pidCorrect = $this->prefix . $match;

        $match = trim($pidCorrect, '.');

        return $match;
    }

    /**
     * @param $raw
     * @param $doi
     * @return string|null
     */
    public function normalizeDoi($raw, $doi): ?string
    {
        if(empty($raw)) return null;

        $doiIncorrect = [];
        foreach($this->prefixInCorrect as $key){
            $doiIncorrect[] = $key . $this->removePrefixFromUrl($doi);
        }

        $raw = str_replace( $doiIncorrect, $doi, $raw);

        return $raw;
    }

    /**
     * @desc Remove https://doi.org/ from DOI URL
     * @param string $url
     * @return string
     */
    public function removePrefixFromUrl(?string $url): string
    {
        if(empty($url)) { return ''; }

        return str_replace($this->prefix, '', $url);
    }
}