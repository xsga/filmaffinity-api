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
use Xsga\FilmAffinityApi\Exceptions\ApiResourceDisabledException;

/**
 * GetTokenController class.
 */
final class GetTokenController extends AbstractController
{
    /**
     * Get token.
     *
     * @param Request  $request  Request.
     * @param Response $response Response.
     *
     * @return Response
     *
     * @throws ApiResourceDisabledException GetToken API's resource disabled.
     *
     * @access public
     */
    public function __invoke(Request $request, Response $response): Response
    {
        if ($_ENV['SECURITY_TYPE'] !== 'token') {
            $errorMsg = 'Get token resource disabled. Security type must be "token"';
            $this->logger->error($errorMsg);
            throw new ApiResourceDisabledException($errorMsg, 1011);
        }//end if

        $this->validateJsonInput($request->getBody(), 'get.token.schema');

        // TODO: llamar al servicio para generar token.
        $token = array();
        $token['token'] = 'generated_token';

        return $this->writeResponse($response, $token);
    }
}
