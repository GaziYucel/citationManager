<?php
/**
 * @file plugins/generic/optimetaCitations/Frontend/Article.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class PluginDAO
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Article view
 */

namespace APP\plugins\generic\optimetaCitations\classes\Frontend;

use APP\plugins\generic\optimetaCitations\classes\PID\Doi;
use APP\plugins\generic\optimetaCitations\classes\PID\OpenAlex;
use APP\plugins\generic\optimetaCitations\classes\PID\Orcid;
use APP\plugins\generic\optimetaCitations\classes\PID\Wikidata;
use APP\plugins\generic\optimetaCitations\OptimetaCitationsPlugin;

class Article
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
     * Returns citations as HTML to show on frontend
     * @param $publication
     * @return string
     */
    public function getCitationsAsHtml($publication): string
    {
        $output = '';

        $citations = $this->plugin->pluginDao->getCitations($publication);

        for ($i = 0; $i < count($citations); $i++) {
            $citationOut = $this->getCitationWithLinks($citations[$i]['raw']);

            if ($citations[$i]['isProcessed']) $citationOut = $this->getSingleCitationAsHtml($citations[$i]);

            $output .= '<p>' . $citationOut . '</p>';
        }

        return $output;
    }

    /**
     * Returns citations as HTML to show on frontend
     * @param $citation
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
        $orcidUrl = "<a href='" . $objOrcid->prefix . "/" . "{orcid}'  target='_blank' class='optimetaButton optimetaButtonGreen'><span>iD</span></a>";
        $wikiDataUrl = "<a href='" . $objWikidata->prefix . "/" . "{wikidata_qid}'  target='_blank' class='optimetaButton optimetaButtonGreen'><span>Wikidata</span></a>";
        $openAlexUrl = "<a href='" . $objOpenAlex->prefix . "/" . "{openalex_id}'  target='_blank' class='optimetaButton optimetaButtonGreen'><span>OpenAlex</span></a>";

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

        if (!empty($citation['wikidata_qid'])) $out .= " " . str_replace('{wikidata_qid}', $citation['wikidata_qid'], $wikiDataUrl);

        if (!empty($citation['openalex_id'])) $out .= " " . str_replace('{openalex_id}', $citation['openalex_id'], $openAlexUrl);

        return $out;
    }

    /**
     * Replace URLs through HTML links, if the citation does not already contain HTML links
     * @param $citation
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