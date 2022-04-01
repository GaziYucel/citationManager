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
		$match = '';
		$rawRemainder = $raw;
		$matches = [];
		preg_match($this->regex, $rawRemainder, $matches);

		if (!empty($matches[0])) {
			$match = $matches[0];
		}

		if (!empty($match)) {
			$rawRemainder = str_replace($match, '', $rawRemainder);
			$match = trim($match, '.');
		}

        $model = new CitationModel();
        $model->url = $match;
        $model->rawRemainder = $rawRemainder;

        return $model;
	}
}
