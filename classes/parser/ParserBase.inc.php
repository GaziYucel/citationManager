<?php
namespace Optimeta\Citations\Parser;

class ParserBase
{
    /**
     * Variable which will hold the raw citations
     *
     * @var string
     */
    protected $citationsRaw = "";

    /**
     * Array which hold the parsed citations
     *
     * @var array
     */
    protected $citationsParsed = [];

    /**
     * Constructor.
     * @param $rawCitations string an unparsed citation string
     */
    function __construct(string $citationsRaw = "")
    {
        $this->citationsRaw = $citationsRaw;
    }

    /**
     * Clean and normalize string
     *
     * @param $text
     * @return string
     */
    protected function cleanCitationString($text): string
    {
        // strip whitespace
        $text = trim($text);

        // strip .
        $text = trim($text, '.');

        // strip ,
        $text = trim($text, ',');

        // strip slashes
        $text = stripslashes($text);

        // normalize whitespace
        $text = preg_replace('/[\s]+/', ' ', $text);

        return $text;
    }

    /**
     * Remove numbers from the beginning of each citation.
     *
     * @param $text
     * @return string
     */
    protected function removeLeadingNumbersFromBeginning($text): string
    {

        $text = preg_replace(
            '/^\s*[\[#]?[0-9]+[.)\]]?\s*/',
            '',
            $text);

        return $text;
    }
}
