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
     */
    private LoadFilm $loadFilm;

    /**
     * Get film.
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        return $this->writeResponse($response, $this->loadFilm->loadFilm($args['id']));
    }
}
