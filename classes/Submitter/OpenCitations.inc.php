<?php
namespace Optimeta\Citations\Submitter;

use Optimeta\Citations\Helpers;
use Optimeta\Citations\Model\WorkModel;
use Optimeta\Shared\OpenCitations\Model\Citation;
use Optimeta\Shared\OpenCitations\Model\Work;
use Optimeta\Shared\OpenCitations\OpenCitationsBase;

class OpenCitations
{
    protected $titleSyntax = 'deposit {{domain}} {{pid}}';

    protected $separator = '===###===@@@===';

    public function submitWork(string $submissionId, array $citations): array
    {
        $citationsOut = $citations;

        $work = new WorkModel();
//        $work = $this->getCurrentSubmission($submissionId);

        $plugin = new \OptimetaCitationsPlugin();
        $request = $plugin->getRequest();
        $context = $request->getContext();

        $openCitations = new OpenCitationsBase();
        $openCitations->setUrl($plugin->getSetting($context->getId(), 'optimetaCitations_open_citations_url'));
        $openCitations->setToken($plugin->getSetting($context->getId(), 'optimetaCitations_open_citations_token'));

        $title = $this->titleSyntax;
        $title = str_replace('{{domain}}', $_SERVER['SERVER_NAME'], $title);
        $title = str_replace('{{pid}}',
            'doi:' . Helpers::removeDoiOrgPrefixFromUrl($work->doi), $title);
        $title = $title . ' [' . date('Y-m-d H:i:s') . ']';
        $body =
            $this->getWorkAsCsv($work) .
            $this->separator . PHP_EOL .
            $this->getCitationsAsCsv($citations);

        $openCitations->depositCitations($title, $body);

        return $citationsOut;
    }

    public function getCurrentSubmission(string $submissionId): object
    {
        $workModel = new WorkModel();

        return $workModel;
    }

    public function getWorkAsCsv(WorkModel $workModel): string
    {
        $work = new Work();
        $work->id = $workModel->doi;
        $work->title = '';
        $work->author = '';
        $work->pub_date = '';
        $work->venue = '';
        $work->volume = '';
        $work->issue = '';
        $work->page = '';
        $work->type = '';
        $work->publisher = '';
        $work->editor = '';

        $names = '';
        foreach ($work as $name => $value) {
            $names .= '"' . str_replace('"', '\"', $name) . '",';
        }
        $names = trim($names, ',');
        $names = $names . PHP_EOL;

        $values = '';
        foreach ($work as $name => $value) {
            $values .= '"' . str_replace('"', '\"', $value) . '",';
        }
        $values = $values . PHP_EOL;

        return $names . $values;
    }

    public function getCitationsAsCsv(array $citations): string
    {
        $names = '';
        foreach (new Citation() as $name => $value) {
            $names .= '"' . str_replace('"', '\"', $name) . '",';
        }
        $names = trim($names, ',');
        $names = $names . PHP_EOL;

        $values = '';
        foreach ($citations as $index => $row) {
            $citation = new Citation();
            $citation->cited_id = '';
            $citation->cited_publication_date = '';
            $citation->citing_id = '';
            $citation->citing_publication_date = '';

            foreach ($citation as $name => $value) {
                $values .= '"' . str_replace('"', '\"', $value) . '",';
            }

            $values = $values . PHP_EOL;
        }

        return $names . $values;
    }
}