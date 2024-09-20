<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Errors\Infrastructure\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Xsga\FilmAffinityApi\Modules\Errors\Application\Services\GetErrorService;
use Xsga\FilmAffinityApi\Modules\Shared\Api\Infrastructure\Controllers\AbstractController;

final class GetErrorController extends AbstractController
{
    public function __construct(private GetErrorService $getErrorService)
    {
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $errorDto = $this->getErrorService->get((int)$args['id']);

        return $this->writeResponse($response, $errorDto);
    }
}
