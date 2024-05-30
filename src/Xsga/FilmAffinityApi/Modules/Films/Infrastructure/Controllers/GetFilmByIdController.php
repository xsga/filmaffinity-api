<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Xsga\FilmAffinityApi\Modules\Films\Application\Services\GetFilmByIdService;
use Xsga\FilmAffinityApi\Modules\Shared\Api\Infrastructure\Controllers\AbstractController;

final class GetFilmByIdController extends AbstractController
{
    public function __construct(private GetFilmByIdService $getFilmByIdService)
    {
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $filmId = (int)$args['id'];
        
        return $this->writeResponse($response, $this->getFilmByIdService->get($filmId));
    }
}
