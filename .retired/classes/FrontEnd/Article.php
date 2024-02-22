<?php
/**
 * @file plugins/generic/citationManager/FrontEnd/Article.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Article
 * @ingroup plugins_generic_citationmanager
 *
 * @brief Article view
 */

namespace APP\plugins\generic\citationManager\classes\FrontEnd;

use APP\plugins\generic\citationManager\classes\PID\Doi;
use APP\plugins\generic\citationManager\classes\PID\OpenAlex;
use APP\plugins\generic\citationManager\classes\PID\Orcid;
use APP\plugins\generic\citationManager\classes\PID\Wikidata;
use APP\plugins\generic\citationManager\CitationManagerPlugin;

class Article
{
    /**
     * @var CitationManagerPlugin
     */
    public CitationManagerPlugin $plugin;

    public function __construct(CitationManagerPlugin $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Returns citations as HTML to show on frontend
     *
     * @param int $publicationId
     *
     * @return string
     */
    public function getCitationsAsHtml(int $publicationId): string
    {
        $output = '';

        $citations = $this->plugin->pluginDao->getCitations($publicationId);

        $count = count($citations);
        for ($i = 0; $i < $count; $i++) {
            $citationOut = $this->getCitationWithLinks($citations[$i]['raw']);

            if ($citations[$i]['isProcessed']) $citationOut = $this->getSingleCitationAsHtml($citations[$i]);

            $output .= '<p>' . $citationOut . '</p>';
        }

        return $output;
    }

    /**
     * Returns citations as HTML to show on frontend
     *
     * @param $citation
     *
     * @return string
     */
    public function getSingleCitationAsHtml($citation): string
    {
        $objOrcid = new Orcid();
        $objWikidata = new Wikidata();
        $objOpenAlex = new OpenAlex();
        $objDoi = new Doi();

        $out = '';
        $doiUrl = "<a href='{doi}'  target='_blank'><span>{doi}</span></a>";
        $orcidUrl = "<a href='" . $objOrcid->prefix . "/" . "{orcid}'  target='_blank' class='citationManagerButton citationManagerButtonGreen'><span>iD</span></a>";
        $wikiDataUrl = "<a href='" . $objWikidata->prefix . "/" . "{wikidata_id}'  target='_blank' class='citationManagerButton citationManagerButtonGreen'><span>Wikidata</span></a>";
        $openAlexUrl = "<a href='" . $objOpenAlex->prefix . "/" . "{openalex_id}'  target='_blank' class='citationManagerButton citationManagerButtonGreen'><span>OpenAlex</span></a>";

        // authors
        foreach ($citation['authors'] as $index => $author) {
            $out .= $author['family_name'] . ' ' . $author['given_name'];
            if (!empty($author['orcid'])) $out .= " " . str_replace('{orcid}', $author['orcid'], $orcidUrl);
            $out .= ', ';
        }
        $out = trim($out, ', ');

        if (!empty($citation['publication_year'])) $out .= ' (' . $citation['publication_year'] . ')';

        $out .= ' ' . $citation['title'];
        
        if (!empty($citation['doi'])) $out .= " " . str_replace('{doi}', $objDoi->prefix . '/' . $citation['doi'], $doiUrl);

        if (!empty($citation['wikidata_id'])) $out .= " " . str_replace('{wikidata_id}', $citation['wikidata_id'], $wikiDataUrl);

        if (!empty($citation['openalex_id'])) $out .= " " . str_replace('{openalex_id}', $citation['openalex_id'], $openAlexUrl);

        return $out;
    }

    /**
     * Replace URLs through HTML links, if the citation does not already contain HTML links
     *
     * @param $citation
     *
     * @return string
     */
    public function getCitationWithLinks($citation): string
    {
        if (stripos($citation, '<a href=') === false) {
            $citation = preg_replace_callback(
                '#(http|https|ftp)://[\d\w\.-]+\.[\w\.]{2,6}[^\s\]\[\<\>]*/?#',
                function ($matches) {
                    $trailingDot = in_array($char = substr($matches[0], -1), array('.', ','));
                    $url = rtrim($matches[0], '.,');
                    return "<a href=\"$url\">$url</a>" . ($trailingDot ? $char : '');
                },
                $citation
            );
        }
        return $citation;
    }
}