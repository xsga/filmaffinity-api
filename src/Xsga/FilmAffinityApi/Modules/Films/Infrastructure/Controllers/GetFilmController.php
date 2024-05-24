<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\App\Infrastructure\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Xsga\FilmAffinityApi\Business\Films\LoadFilm;

final class GetFilmController extends AbstractController
{
    /**
     * @Inject
     */
    private LoadFilm $loadFilm;

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        return $this->writeResponse($response, $this->loadFilm->loadFilm($args['id']));
    }
}
