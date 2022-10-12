<?php

/**
 * GetCountriesController.
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
use Xsga\FilmAffinityApi\Business\Countries\Countries;

/**
 * GetCountriesController class.
 */
final class GetCountriesController extends AbstractController
{
    /**
     * Advanced search service.
     *
     * @Inject
     * @var Countries
     *
     * @access private
     */
    private $countries;

    /**
     * Get genres.
     *
     * @param Request  $request  Request.
     * @param Response $response Response.
     *
     * @return Response
     *
     * @access public
     */
    public function get(Request $request, Response $response): Response
    {
        return $this->writeResponse($response, $this->countries->getAll());
    }
}
