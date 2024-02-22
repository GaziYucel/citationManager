<?php
/**
 * @file classes/FrontEnd/ArticleView.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class ArticleView
 * @brief Article page view
 */

namespace APP\plugins\generic\citationManager\classes\FrontEnd;

use Application;
use APP\plugins\generic\citationManager\CitationManagerPlugin;
use APP\plugins\generic\citationManager\classes\Db\PluginDAO;
use APP\plugins\generic\citationManager\classes\PID\Doi;
use APP\plugins\generic\citationManager\classes\PID\OpenAlex;
use APP\plugins\generic\citationManager\classes\PID\Orcid;
use APP\plugins\generic\citationManager\classes\PID\Wikidata;
use Publication;
use TemplateManager;
use SmartyException;

class ArticleView
{
    /** @var CitationManagerPlugin */
    public CitationManagerPlugin $plugin;

    /** @param CitationManagerPlugin $plugin */
    public function __construct(CitationManagerPlugin $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Hook callback: register output filter to replace raw with structured citations.
     *
     * @param string $hookName
     * @param array $args
     * @return bool
     */
    public function execute(string $hookName, array $args): bool
    {
        /* @var TemplateManager $templateMgr */
        $templateMgr = $args[0];
        $template = $args[1];

        $showStructured = $this->plugin->getSetting($this->plugin->getCurrentContextId(),
            CitationManagerPlugin::CITATION_MANAGER_FRONTEND_SHOW_STRUCTURED);

        switch ($template) {
            case 'frontend/pages/article.tpl':
                if ($showStructured === 'true') {
                    $templateMgr->addStyleSheet(
                        'citationManager',
                        $this->plugin->templateParameters['assetsUrl'] . '/css/frontend.css', ['contexts' => ['frontend']]
                    );

                    try {
                        $templateMgr->registerFilter("output", array($this, 'registerFilter'));
                    } catch (SmartyException $e) {
                        error_log(__METHOD__ . ' ' . $e->getMessage());
                    }
                }
                break;
            default:
                break;
        }

        return false;
    }

    /**
     * Output filter to replace raw with structured citations.
     *
     * @param $output
     * @param $templateMgr
     * @return string
     */
    public function registerFilter($output, $templateMgr): string
    {
        $request = Application::get()->getRequest();
        $context = $request->getContext();

        /* @var Publication $publication */
        $publication = $templateMgr->getTemplateVars('currentPublication');

        $references = $this->getCitationsAsHtml($publication->getId());

        $id = CITATION_MANAGER_PLUGIN_NAME . '_6ae88';
        $newOutput =
            "<div id='$id' style='display: none;'>$references</div>
            <script> 
                window.onload = function(){
                    let src = document.querySelector('#$id');
                    let dst = document.querySelector('.main_entry .references .value');
                    dst.innerHTML = src.innerHTML;
                }
            </script>";

        if ($context != null) {
            $output .= $newOutput;
            $templateMgr->unregisterFilter("output", array($this, 'registerFilter'));
        }

        return $output;
    }

    /**
     * Returns citations as HTML to show on frontend
     *
     * @param int $publicationId
     * @return string
     */
    public function getCitationsAsHtml(int $publicationId): string
    {
        $output = '';

        $pluginDao = new PluginDAO();

        $citations = $pluginDao->getCitations($publicationId);

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
     * @return string
     */
    public function getSingleCitationAsHtml($citation): string
    {
        $out = '<!-- structured -->';

        foreach ($citation as $key => $value) {
            switch ($key){
                case 'raw':
                case 'authors':
                case 'doi':
                case 'wikidata_id':
                case 'openalex_id':
                case 'github_issue_id':
                case 'isProcessed':
                case 'type':
                case 'volume':
                case 'issue':
                case 'pages':
                case 'first_page':
                case 'last_page':
                case 'abstract':
                    break;
                case 'publication_year':
                    if (!empty($citation['publication_year'])) $out .= ' (' . $citation['publication_year'] . ') ';
                    break;
                case 'publication_date':
                    if (!empty($citation['publication_date'])) $out .= ' (' . date_format(date_create($citation['publication_date']), "d-m-Y") . ') ';
                    break;
                default:
                    $out .= $value . ' ';
                    break;
            }
        }

        $out .= '<br/>';

        // authors
        $orcidUrl = "<a href='" . Orcid::prefix . "/{orcid}' target='_blank' class=''><span>{name}</span></a>";
        foreach ($citation['authors'] as $author) {
            if (!empty($author['orcid_id'])) {
                $out .= " " . str_replace(
                        ['{orcid}', '{name}'],
                        [$author['orcid_id'], $author['family_name'] . ' ' . $author['given_name']],
                        $orcidUrl);
            } else {
                $out .= $author['family_name'] . ' ' . $author['given_name'];
            }
            $out .= ', ';
        }
        $out = trim($out, ', ');

        $out .= '<br/>';

        // external ids
        $doiUrl = "<a href='" . Doi::prefix . "/{doi}' target='_blank' class='citationManager-Button citationManager-ButtonGreen'><span>doi</span></a>";
        $wikiDataUrl = "<a href='" . Wikidata::prefix . "/{wikidata_id}' target='_blank' class='citationManager-Button citationManager-ButtonGreen'><span>Wikidata</span></a>";
        $openAlexUrl = "<a href='" . OpenAlex::prefix . "/{openalex_id}' target='_blank' class='citationManager-Button citationManager-ButtonGreen'><span>OpenAlex</span></a>";

        if (!empty($citation['doi'])) $out .= " " . str_replace('{doi}', $citation['doi'], $doiUrl);
        if (!empty($citation['wikidata_id'])) $out .= " " . str_replace('{wikidata_id}', $citation['wikidata_id'], $wikiDataUrl);
        if (!empty($citation['openalex_id'])) $out .= " " . str_replace('{openalex_id}', $citation['openalex_id'], $openAlexUrl);

        $out .= '<!-- structured -->';

        $out = preg_replace('!\s+!', ' ', $out);

        return $out;
    }

    /**
     * Replace URLs through HTML links, if the citation does not already contain HTML links
     *
     * @param string $citation
     * @return string
     */
    public function getCitationWithLinks(string $citation): string
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
