<?php
namespace Optimeta\Citations;

class Helpers
{
    /**
     * @desc Remove https://openalex.org/ from OpenAlex URL
     * @param string $url
     * @return string
     */
    public static function removeOpenAlexOrgFromUrl(?string $url): string
    {
        if(empty($url)) { return ''; }
        return str_replace('https://openalex.org/', '', $url);
    }

    /**
     * @desc Remove https://doi.org/ from DOI URL
     * @param string $url
     * @return string
     */
    public static function removeDoiOrgPrefixFromUrl(?string $url): string
    {
        import('plugins.generic.optimetaCitations.classes.Pid.Doi');
        $obj = new \Optimeta\Citations\Pid\Doi();
        return $obj->removePrefixFromUrl($url);
    }

    /**
     * @desc Remove https://orcid.org/ from ORCID URL
     * @param string $url
     * @return string
     */
    public static function removeOrcidOrgPrefixFromUrl(?string $url): string
    {
        import('plugins.generic.optimetaCitations.classes.Pid.Orcid');
        $obj = new \Optimeta\Citations\Pid\Orcid();
        return $obj->removePrefixFromUrl($url);
    }

    /**
     * @desc Normalize line endings of string
     * @param string $text
     * @return string
     */
    public static function normalizeLineEndings(string $text): string
    {
        if(empty($text)) { return ''; }
        return preg_replace(
            '/[\r\n]+/s',
            "\n",
            $text);
    }

    /**
     * @desc Normalize whitespace
     * @param string $text
     * @return string
     */
    public static function normalizeWhiteSpace(string $text): string
    {
        if(empty($text)) { return ''; }
        return preg_replace(
            '/[\s]+/',
            ' ',
            $text);
    }

    /**
     * @desc Remove number from the beginning of string.
     * @param $text
     * @return string
     */
    public static function removeNumberPrefixFromString(string $text): string
    {
        if(empty($text)) { return ''; }
        return preg_replace(
            '/^\s*[\[#]?[0-9]+[.)\]]?\s*/',
            '',
            $text);
    }
}
