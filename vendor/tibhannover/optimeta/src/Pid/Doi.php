<?php
namespace Optimeta\Shared\Pid;

class Doi
{
    /**
     * @desc Regex to extract DOI
     * @var string
     */
    public string $regex = '(10[.][0-9]{4,}[^\s"/<>]*/[^\s"<>]+)';

    /**
     * @desc Correct prefix
     * @var string
     */
    public string $prefix = 'https://doi.org/';

    /**
     * @desc Incorrect prefixes
     * @var array|string[]
     */
    public array $prefixInCorrect = [
        'http://dx.doi.org/',
        'http://doi.org/',
        'http://www.doi.org/',
        'https://www.doi.org/',
        'http://doi:',
        'https://doi:',
        'doi:',
        'doi: '
    ];

    /**
     * Extract doi from string
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

        return trim($pidCorrect, '.');
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

        return str_replace( $doiIncorrect, $doi, $raw);
    }

    /**
     * @desc Remove $prefix from URL
     * @param ?string $url
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