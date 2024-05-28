<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\App\Infrastructure\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Xsga\FilmAffinityApi\Modules\Shared\Api\Infrastructure\Controllers\AbstractController;

final class GetAllGenresController extends AbstractController
{
    public function __construct(private GetAllGenresService $getAllGenresService)
    {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        return $this->writeResponse($response, $this->getAllGenresService->get());
    }
}
