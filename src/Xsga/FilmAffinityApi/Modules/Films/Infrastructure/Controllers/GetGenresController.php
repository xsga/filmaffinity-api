<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\App\Infrastructure\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Xsga\FilmAffinityApi\Business\Genres\Genres;

final class GetGenresController extends AbstractController
{
    /**
     * @Inject
     */
    private Genres $genres;

    public function __invoke(Request $request, Response $response): Response
    {
        return $this->writeResponse($response, $this->genres->getAll());
    }
}
