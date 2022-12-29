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
     */
    private Countries $countries;

    /**
     * Get genres.
     */
    public function __invoke(Request $request, Response $response): Response
    {
        return $this->writeResponse($response, $this->countries->getAll());
    }
}
