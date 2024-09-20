<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Errors\Infrastructure\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Xsga\FilmAffinityApi\Modules\Errors\Application\Services\GetAllErrorsService;
use Xsga\FilmAffinityApi\Modules\Shared\Api\Infrastructure\Controllers\AbstractController;

final class GetAllErrorsController extends AbstractController
{
    public function __construct(private GetAllErrorsService $getAllErrorsService)
    {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $errorsDto = $this->getAllErrorsService->get();

        return $this->writeResponse($response, $errorsDto);
    }
}
