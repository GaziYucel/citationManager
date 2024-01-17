<?php
/**
 * @file plugins/generic/optimetaCitations/classes/Handler/ParserHandler.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class ParserHandler
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief ParserHandler class for extracting DOI, Url, Handle, Arxiv, Urn.
 */

namespace APP\plugins\generic\optimetaCitations\classes\Handler;

use APP\plugins\generic\optimetaCitations\classes\Model\CitationModel;
use APP\plugins\generic\optimetaCitations\classes\PID\Arxiv;
use APP\plugins\generic\optimetaCitations\classes\PID\Doi;
use APP\plugins\generic\optimetaCitations\classes\PID\Handle;
use APP\plugins\generic\optimetaCitations\classes\PID\Url;
use APP\plugins\generic\optimetaCitations\classes\PID\Urn;
use APP\plugins\generic\optimetaCitations\OptimetaCitationsPlugin;

class ParserHandler
{
    /**
     * @var OptimetaCitationsPlugin
     */
    public OptimetaCitationsPlugin $plugin;

    public function __construct(OptimetaCitationsPlugin $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Parse and save parsed citations to citationsParsed
     * @param string $citationsRaw
     * @return array
     */
    public function executeAndReturnCitations(string $citationsRaw): array
    {
        $citations = [];

        // cleanup citationsRaw
        $citationsRaw = $this->cleanCitationsRaw($citationsRaw);

        // return if input is empty
        if (empty($citationsRaw)) {
            return $citations;
        }

        // break up at line endings
        $citationsArray = explode("\n", $citationsRaw);

        // loop through citations and parse every citation
        foreach ($citationsArray as $index => $rowRaw) {

            // get data model and fill citation raw
            $citation = new CitationModel();
            $citation->raw = $rowRaw;

            // clean single citation
            $citation->raw = $this->cleanCitation($citation->raw);

            // remove numbers from the beginning of each citation
            $citation->raw = $this->removeNumberPrefixFromString($citation->raw);

            // parse doi
            $objDoi = new Doi();
            $citation->doi = $objDoi->getDoiParsed($citation->raw);
            $citation->raw = $objDoi->normalizeDoi($citation->raw, $citation->doi);

            // parse url (after parsing doi)
            $objUrl = new Url();
            $citation->url = $objUrl->getUrlParsed(str_replace($citation->doi, '', $citation->raw));

            // replace incorrect url by correct url
            $objHandle = new Handle();
            $citation->url = str_replace($objHandle->prefixInCorrect, $objHandle->prefix, $citation->url);
            $objArxiv = new Arxiv();
            $citation->url = str_replace($objArxiv->prefixInCorrect, $objArxiv->prefix, $citation->url);

            // urn parser
            $objUrn = new Urn();
            $citation->urn = $objUrn->getUrnParsed($citation->raw);

            // push to citations parsed array
            $citations[] = (array)$citation;
        }

        return $citations;
    }

    /**
     * Clean and return citationRaw
     * @param string $citationsRaw
     * @return string
     */
    private function cleanCitationsRaw(string $citationsRaw): string
    {
        // strip whitespace
        $citationsRaw = trim($citationsRaw);

        // strip slashes
        $citationsRaw = stripslashes($citationsRaw);

        // normalize line endings.
        $citationsRaw = $this->normalizeLineEndings($citationsRaw);

        // remove trailing/leading line breaks.
        $citationsRaw = trim($citationsRaw, "\n");

        return $citationsRaw;
    }

    /**
     * Clean and return citation
     * @param $citation
     * @return string
     */
    private function cleanCitation($citation): string
    {
        // strip whitespace
        $citation = trim($citation);

        // trim .,
        $citation = trim($citation, '.,');

        // strip slashes
        $citation = stripslashes($citation);

        // normalize whitespace
        $citation = $this->normalizeWhiteSpace($citation);

        return $citation;
    }

    /**
     * Remove number from the beginning of string.
     * @param string $text
     * @return string
     */
    public function removeNumberPrefixFromString(string $text): string
    {
        if (empty($text)) {
            return '';
        }
        return preg_replace(
            '/^\s*[\[#]?[0-9]+[.)\]]?\s*/',
            '',
            $text);
    }

    /**
     * Normalize whitespace
     * @param string $text
     * @return string
     */
    public static function normalizeWhiteSpace(string $text): string
    {
        if (empty($text)) {
            return '';
        }
        return preg_replace(
            '/[\s]+/',
            ' ',
            $text);
    }

    /**
     * Normalize line endings of string
     * @param string $text
     * @return string
     */
    public static function normalizeLineEndings(string $text): string
    {
        if (empty($text)) {
            return '';
        }
        return preg_replace(
            '/[\r\n]+/s',
            "\n",
            $text);
    }
}
