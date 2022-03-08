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
		$doi = '';
		$rawRemainder = $raw;
		$doiArray = [];
		preg_match($this->regex, $rawRemainder, $doiArray);

		if (!empty($doiArray[0])) {
			$doi = $doiArray[0];
		}

		if (!empty($doi)) {
			$pidCorrect = 'https://doi.org/' . $doi;

			$prefix = [
				'http://doi.org/' . $doi,
				'http://www.doi.org/' . $doi,
				'https://www.doi.org/' . $doi,
				'http://doi:' . $doi,
				'https://doi:' . $doi,
				'doi:' . $doi,
				'doi: ' . $doi
			];

			$rawRemainder = str_replace($prefix, $pidCorrect, $rawRemainder);
			$rawRemainder = str_replace($pidCorrect, '', $rawRemainder);
			$rawRemainder = str_replace($doi, '', $rawRemainder);

			$doi = trim($pidCorrect, '.');
		}

        $model = new CitationModel();
        $model->doi = $doi;
        $model->rawRemainder = $rawRemainder;

        return $model;
	}
}
