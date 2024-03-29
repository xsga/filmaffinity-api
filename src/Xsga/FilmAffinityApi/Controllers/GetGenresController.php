<?php

/**
 * GetGenresController.
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
use Xsga\FilmAffinityApi\Business\Genres\Genres;

/**
 * Class GetGenresController.
 */
final class GetGenresController extends AbstractController
{
    /**
     * Advanced search service.
     *
     * @Inject
     */
    private Genres $genres;

    /**
     * Get genres.
     */
    public function __invoke(Request $request, Response $response): Response
    {
        return $this->writeResponse($response, $this->genres->getAll());
    }
}
