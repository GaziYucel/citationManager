<?php
namespace Optimeta\Citations\Parser;

use Optimeta\Citations\Model\CitationModel;

class ParserURL
{
	/**
	 * Regex to extract URL
	 *
	 * @var string
	 */
	//private $regex = '(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)';
    private $regex = '%\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))%s';

    /**
     * @param $raw
     * @return CitationModel
     */
	public function getParsed($raw): CitationModel
	{
		$url = '';
		$rawRemainder = $raw;
		$urlArray = [];
		preg_match($this->regex, $rawRemainder, $urlArray);

		if (!empty($urlArray[0])) {
			$url = $urlArray[0];
		}

		if (!empty($url)) {
			$rawRemainder = str_replace($url, '', $rawRemainder);
			$url = trim($url, '.');
		}

        $model = new CitationModel();
        $model->url = $url;
        $model->rawRemainder = $rawRemainder;

        return $model;
	}
}
