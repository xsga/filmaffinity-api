<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Xsga\FilmAffinityApi\Modules\Films\Application\Services\SimpleSearchService;
use Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Controllers\Mappers\JsonSimpleSearchToSearchDto;
use Xsga\FilmAffinityApi\Modules\Shared\Api\Infrastructure\Controllers\AbstractController;

final class SimpleSearchController extends AbstractController
{
    private string $schema = 'simple.search.schema';

    public function __construct(
        private SimpleSearchService $simpleSearchService,
        private JsonSimpleSearchToSearchDto $inputToSearchDto
    ) {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $this->validateJson((string)$request->getBody(), $this->schema);

        $searchDto = $this->inputToSearchDto->convert($request->getParsedBody());

        return $this->writeResponse($response, $this->simpleSearchService->search($searchDto));
    }
}
