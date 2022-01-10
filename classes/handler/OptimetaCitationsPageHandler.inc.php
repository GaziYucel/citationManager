<?php
/**
 * @file plugins/generic/optimetaCitations/classes/handler/OptimetaCitationsPageHandler.inc.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class OptimetaCitationsPageHandler
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Handler Class OptimetaCitationsPageHandler
 */

import('classes.handler.Handler');
import('plugins.generic.optimetaCitations.classes.OptimetaCitationsParser');

require_once ('/home2/kd94958/public_www/yucel.nl_ojs330/plugins/generic/optimetaCitations/_helper/debug.php');

// https://ojs330.yucel.nl/index.php/ojs/optimetaCitations/parse
// post: submissionId, citationsRaw

class OptimetaCitationsPageHandler extends Handler
{
	private $submissionId 		= '';
	private $citationsRaw 		= '';
	private $citationsParsed 	= '[]';

	private $response			= [ 'submissionId' => '', 'citationsRaw' => '', 'citationsParsed' => '[]' ];

	public function parse($args, $request)
	{

		// check if citationsRaw is given in request and use that
		if ($request->getUserVars() && sizeof($request->getUserVars()) > 0 &&
			isset($request->getUserVars()['citationsRaw'])){
			$this->citationsRaw = trim($request->getUserVars()['citationsRaw']);
		}

		// citationsRaw not found, return false
		if(strlen($this->citationsRaw) === 0){
			return new JSONMessage(false, $this->response);
		}

		// citationsRaw found, assign to response
		$this->response['citationsRaw'] = $this->citationsRaw;

		// citationsRaw found, parse citations
		$parser = new OptimetaCitationsParser($this->citationsRaw);
		$this->citationsParsed = $parser->getCitationsParsedJson();

		// citations parsed, assign to response
		$this->response['citationsParsed'] = $this->citationsParsed;

		return new JSONMessage(true, $this->response);
	}

	private function parse___OnHold__($args, $request)
	{
		if ($request->getUserVars() && sizeof($request->getUserVars()) > 0 &&
			isset($request->getUserVars()['submissionId'])){
			$this->submissionId = $request->getUserVars()['submissionId'];
		}

		// if submissionId not found, return false
		if(strlen($this->submissionId) === 0){ return new JSONMessage(false, $this->response); }

		// submissionId found, assign to response
		$this->response['submissionId'] = $this->submissionId;

		// check if citationsRaw is given in request and use that
		if(isset($request->getUserVars()['citationsRaw'])){
			$this->citationsRaw = trim($request->getUserVars()['citationsRaw']);
		}

		// citationsRaw is not given in request, use the value in database
		if(strlen($this->citationsRaw) == 0){
			$publicationDao = DAORegistry::getDAO('PublicationDAO');
			$publication = $publicationDao->getById($this->submissionId);
			$this->citationsRaw = $publication->getData('citationsRaw');
		}

		// citationsRaw not found, return false
		if(strlen($this->citationsRaw) == 0){ return new JSONMessage(false, $this->response); }

		// citationsRaw found, assign to response
		$this->response['citationsRaw'] = $this->citationsRaw;

		// citationsRaw found, parse citations
		$parser = new OptimetaCitationsParser($this->citationsRaw);
		$this->citationsParsed = $parser->getCitationsParsedJson();

		// citations parsed, assign to response
		$this->response['citationsParsed'] = $this->citationsParsed;

		return new JSONMessage(true, $this->response);
	}

}
