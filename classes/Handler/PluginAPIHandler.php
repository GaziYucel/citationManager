<?php
/**
 * @file plugins/generic/optimetaCitations/classes/Handler/PluginAPIHandler.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class PluginAPIHandler
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Extended class of base request APIhandler
 */

namespace APP\plugins\generic\optimetaCitations\classes\Handler;

use APP\plugins\generic\optimetaCitations\OptimetaCitationsPlugin;
use Exception;
use PKP\core\APIResponse;
use PKP\handler\APIHandler;
use PKP\security\authorization\PolicySet;
use PKP\security\authorization\RoleBasedHandlerOperationPolicy;
use PKP\security\Role;
use Slim\Http\Request as SlimRequest;
use Slim\Http\Response;

class PluginAPIHandler extends APIHandler
{
    /**
     * @var OptimetaCitationsPlugin
     */
    public OptimetaCitationsPlugin $plugin;

    /**
     * Structure of the response body
     * @var array
     */
    private array $responseBody = [
        'status' => 'ok',
        'message-type' => '',
        'message-version' => '1',
        'message' => ''
    ];

    public function __construct(OptimetaCitationsPlugin $plugin)
    {
        $this->plugin = $plugin;

        $this->_handlerPath = OptimetaCitationsPlugin::OPTIMETA_CITATIONS_API_ENDPOINT;

        $this->_endpoints = [
            'POST' => [
                [
                    'pattern' => $this->getEndpointPattern() . '/process',
                    'handler' => [$this, 'process'],
                    'roles' => [Role::ROLE_ID_MANAGER, Role::ROLE_ID_SUB_EDITOR, Role::ROLE_ID_ASSISTANT, Role::ROLE_ID_REVIEWER, Role::ROLE_ID_AUTHOR],
                ],
                [
                    'pattern' => $this->getEndpointPattern() . '/deposit',
                    'handler' => [$this, 'deposit'],
                    'roles' => [Role::ROLE_ID_MANAGER, Role::ROLE_ID_SUB_EDITOR, Role::ROLE_ID_ASSISTANT, Role::ROLE_ID_REVIEWER, Role::ROLE_ID_AUTHOR],
                ]
            ],
            'GET' => []
        ];

        parent::__construct();
    }

    /**
     * @copydoc APIHandler::authorize
     */
    public function authorize($request, &$args, $roleAssignments): bool
    {
        $rolePolicy = new PolicySet(COMBINING_PERMIT_OVERRIDES);

        foreach ($roleAssignments as $role => $operations) {
            $rolePolicy->addPolicy(new RoleBasedHandlerOperationPolicy($request, $role, $operations));
        }
        $this->addPolicy($rolePolicy);

        return parent::authorize($request, $args, $roleAssignments);
    }

    /**
     * Parse and enrich citations and return
     * @param SlimRequest $slimRequest
     * @param APIResponse $response
     * @param array $args
     * @return Response
     */
    public function process(SlimRequest $slimRequest, APIResponse $response, array $args): Response
    {
        $request = $this->getRequest();
        $submissionId = '';
        $citationsRaw = '';
        $citationsOut = [];

        $this->responseBody['message-type'] = 'citations';

        // check if GET/POST filled
        if ($request->getUserVars() && sizeof($request->getUserVars()) > 0) {
            if (isset($request->getUserVars()['submissionId'])) {
                $submissionId = trim($request->getUserVars()['submissionId']);
            }
            if (isset($request->getUserVars()['citationsRaw'])) {
                $citationsRaw = trim($request->getUserVars()['citationsRaw']);
            }
        }

        // citationsRaw empty, response empty array
        if (strlen($citationsRaw) === 0) {
            return $response->withJson($this->responseBody, 200);
        }

        // parse citations
        $parser = new ParserHandler($this->plugin);
        $citationsOut = $parser->executeAndReturnCitations($citationsRaw);

        // enrich citations
        $enricher = new EnricherHandler($this->plugin);
        $citationsOut = $enricher->executeAndReturnCitations($citationsOut);

        $this->responseBody['message'] = $citationsOut;
        return $response->withJson($this->responseBody, 200);
    }

    /**
     * Deposit citations and return
     * @param SlimRequest $slimRequest
     * @param APIResponse $response
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function deposit(SlimRequest $slimRequest, APIResponse $response, array $args): Response
    {
        $request = $this->getRequest();
        $submissionId = '';
        $citationsIn = [];
        $workOut = [];

        $this->responseBody['message-type'] = 'deposit';

        // check if GET/POST filled
        if ($request->getUserVars() && sizeof($request->getUserVars()) > 0) {
            if (isset($request->getUserVars()['submissionId'])) {
                $submissionId = trim($request->getUserVars()['submissionId']);
            }
            if (isset($request->getUserVars()['citations'])) {
                $citationsIn = json_decode(trim($request->getUserVars()['citations']), true);
            }
        }

        // citations empty, response empty array
        if (empty($submissionId) || empty($citationsIn)) {
            return $response->withJson($this->responseBody, 200);
        }

        // deposit work + citations
        $depositor = new DepositorHandler($this->plugin);
        $workOut = $depositor->executeAndReturnWork($submissionId, $citationsIn);

        $this->responseBody['message'] = $workOut;
        return $response->withJson($this->responseBody, 200);
    }
}
