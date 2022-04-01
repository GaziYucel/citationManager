<?php
namespace Optimeta\Citations\Parser;

use Optimeta\Citations\Model\CitationModel;

class ParserURN
{
	/**
	 * Regex to extract URN
	 *
	 * @var string
	 */
    private $regex = '/urn:([a-z0-9][a-z0-9-]{1,31}):((?:[-a-z0-9()+,.:=@;$_!*\'&~\/]|%[0-9a-f]{2})+)(?:(\?\+)((?:(?!\?=)(?:[-a-z0-9()+,.:=@;$_!*\'&~\/\?]|%[0-9a-f]{2}))*))?(?:(\?=)((?:(?!#).)*))?(?:(#)((?:[-a-z0-9()+,.:=@;$_!*\'&~\/\?]|%[0-9a-f]{2})*))?$/i';

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
        $model->urn = $match;
        $model->rawRemainder = $rawRemainder;

        return $model;
	}
}
