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
     * @desc Add https://openalex.org/ from OpenAlex URL
     * @param string $url
     * @return string
     */
    public static function addOpenAlexOrgFromUrl(?string $url): string
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
        if(empty($url)) { return ''; }
        return str_replace('https://doi.org/', '', $url);
    }
    /**
     * @desc Add https://doi.org/ from DOI URL
     * @param string $url
     * @return string
     */
    public static function addDoiOrgPrefixFromUrl(?string $url): string
    {
        if(empty($url)) { return ''; }
        return str_replace('https://doi.org/', '', $url);
    }

    /**
     * @desc Remove https://orcid.org/ from ORCID URL
     * @param string $url
     * @return string
     */
    public static function removeOrcidOrgPrefixFromUrl(?string $url): string
    {
        if(empty($url)) { return ''; }
        return str_replace('https://orcid.org/', '', $url);
    }
    /**
     * @desc Add https://orcid.org/ from ORCID URL
     * @param string $url
     * @return string
     */
    public static function addOrcidOrgPrefixFromUrl(?string $url): string
    {
        if(empty($url)) { return ''; }
        return str_replace('https://orcid.org/', '', $url);
    }
}
