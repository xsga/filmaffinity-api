<?php

/**
 * GetTokenController.
 *
 * PHP Version 8
 *
 * @author  xsga <parker@xsga.es>
 * @license MIT
 * @version 1.0.0
 */

/**
 * Namespace.
 */
namespace Xsga\FilmAffinityApi\Controllers;

/**
 * Import dependencies.
 */
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Xsga\FilmAffinityApi\Business\Users\GetToken;

/**
 * GetTokenController class.
 */
final class GetTokenController extends AbstractController
{
    /**
     * JWT service.
     *
     * @Inject
     * @var GetToken
     *
     * @access private
     */
    private $getToken;

    /**
     * Get token.
     *
     * @param Request  $request  Request.
     * @param Response $response Response.
     *
     * @return Response
     *
     * @access public
     */
    public function __invoke(Request $request, Response $response): Response
    {
        $this->validateJsonInput($request->getBody(), 'get.token.schema');

        $body = $request->getParsedBody();

        $token          = array();
        $token['token'] = $this->getToken->get($body['user'], $body['password']);

        return $this->writeResponse($response, $token);
    }
}
