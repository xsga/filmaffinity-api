<?php

/**
 * GetFilmController.
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
use Xsga\FilmAffinityApi\Business\Films\LoadFilm;

/**
 * Class GetFilmController.
 */
final class GetFilmController extends AbstractController
{
    /**
     * Load film service.
     *
     * @Inject
     * @var LoadFilm
     *
     * @access private
     */
    private $loadFilm;

    /**
     * Get film.
     *
     * @param Request  $request  Request.
     * @param Response $response Response.
     * @param array    $args     Additional URI parameters (ID).
     *
     * @return Response
     *
     * @access public
     */
    public function get(Request $request, Response $response, array $args): Response
    {
        return $this->writeResponse($response, $this->loadFilm->loadFilm($args['id']));
    }
}
