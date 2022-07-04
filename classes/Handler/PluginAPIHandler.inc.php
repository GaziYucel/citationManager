<?php
/**
 * @file plugins/generic/optimetaCitations/classes/Handler/PluginAPIHandler.inc.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class PluginAPIHandler
 * @ingroup Handler
 *
 * @brief Extended class of base request API handler
 *
 */
namespace Optimeta\Citations\Handler;

import('lib.pkp.classes.security.authorization.PolicySet');
import('lib.pkp.classes.security.authorization.RoleBasedHandlerOperationPolicy');
import('plugins.generic.optimetaCitations.classes.Enricher.Enricher');
import('plugins.generic.optimetaCitations.classes.Submitter.Submitter');

use APIHandler;
use Optimeta\Citations\Submitter\Submitter;
use RoleBasedHandlerOperationPolicy;
use PolicySet;
use GuzzleHttp\Exception\GuzzleException;
use Optimeta\Citations\Parser\Parser;
use Optimeta\Citations\Enricher\Enricher;

class PluginAPIHandler extends APIHandler
{
    private $apiEndpoint = OPTIMETA_CITATIONS_API_ENDPOINT;

    private $submissionId = '';
    private $citationsRaw = '';
    private $citations = [];

    private $responseBody = [
        'status' => 'ok',
        'message-type' => '',
        'message-version' => '1',
        'message' => ''
    ];

    public function __construct()
    {
        $this->_handlerPath = $this->apiEndpoint;

        $this->_endpoints = [
            'POST' => [
                [
                    'pattern' => $this->getEndpointPattern() . '/process',
                    'handler' => [$this, 'process'],
                    'roles' => [ROLE_ID_MANAGER, ROLE_ID_SUB_EDITOR, ROLE_ID_ASSISTANT, ROLE_ID_REVIEWER, ROLE_ID_AUTHOR],
                ]
            ],
            'GET' => []
        ];

        parent::__construct();
    }

    /**
     * @copydoc APIHandler::authorize
     */
    public function authorize($request, &$args, $roleAssignments)
    {
        $rolePolicy = new PolicySet(COMBINING_PERMIT_OVERRIDES);

        foreach ($roleAssignments as $role => $operations) {
            $rolePolicy->addPolicy(new RoleBasedHandlerOperationPolicy($request, $role, $operations));
        }
        $this->addPolicy($rolePolicy);

        return parent::authorize($request, $args, $roleAssignments);
    }

    /**
     * @desc Parse and enrich citations and return
     * @param $slimeRequest
     * @param $response
     * @param $args
     * @return mixed
     * @throws GuzzleException
     */
    public function process($slimeRequest, $response, $args)
    {
        $request = $this->getRequest();

        $this->responseBody['message-type'] = 'citations';

        // check if GET/POST filled
        if ($request->getUserVars() && sizeof($request->getUserVars()) > 0) {
            if(isset($request->getUserVars()['submissionId'])){
                $this->submissionId = trim($request->getUserVars()['submissionId']);
            }
            if(isset($request->getUserVars()['citationsRaw'])){
                $this->citationsRaw = trim($request->getUserVars()['citationsRaw']);
            }
        }

        // citationsRaw empty, response empty array
        if (strlen($this->citationsRaw) === 0) {
            $this->responseBody['status'] = 'ok';
            return $response->withJson($this->responseBody, 200);
        }

        // parse citations
        $parser = new Parser($this->citationsRaw);
        $this->citations = $parser->getCitations();

        // enrich citations
        $enricher = new Enricher($this->citations);
        $this->citations = $enricher->getCitations();

        $this->responseBody['message'] = $this->citations;
        return $response->withJson($this->responseBody, 200);
    }
}
