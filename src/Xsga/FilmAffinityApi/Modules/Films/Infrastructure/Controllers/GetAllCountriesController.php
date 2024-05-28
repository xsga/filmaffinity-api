<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Xsga\FilmAffinityApi\Modules\Films\Application\Services\GetAllCountriesService;
use Xsga\FilmAffinityApi\Modules\Shared\Api\Infrastructure\Controllers\AbstractController;

final class GetAllCountriesController extends AbstractController
{
    public function __construct(private GetAllCountriesService $getAllCountriesService)
    {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        return $this->writeResponse($response, $this->getAllCountriesService->get());
    }
}
