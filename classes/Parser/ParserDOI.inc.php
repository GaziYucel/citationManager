<?php
namespace Optimeta\Citations\Parser;

use Optimeta\Citations\Model\CitationModel;

class ParserDOI
{
	/**
	 * Regex to extract DOI
	 *
	 * @var string
	 */
	private $regex = '(10[.][0-9]{4,}[^\s"/<>]*/[^\s"<>]+)';

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
			$pidCorrect = 'https://doi.org/' . $match;

			$prefix = [
				'http://doi.org/' . $match,
				'http://www.doi.org/' . $match,
				'https://www.doi.org/' . $match,
				'http://doi:' . $match,
				'https://doi:' . $match,
				'doi:' . $match,
				'doi: ' . $match
			];

			$rawRemainder = str_replace($prefix, $pidCorrect, $rawRemainder);
			$rawRemainder = str_replace($pidCorrect, '', $rawRemainder);
			$rawRemainder = str_replace($match, '', $rawRemainder);

			$match = trim($pidCorrect, '.');
		}

        $model = new CitationModel();
        $model->doi = $match;
        $model->rawRemainder = $rawRemainder;

        return $model;
	}
}
