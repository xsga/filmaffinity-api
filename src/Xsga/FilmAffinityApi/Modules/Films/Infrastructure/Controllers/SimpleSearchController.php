<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\SearchDto;
use Xsga\FilmAffinityApi\Modules\Films\Application\Services\SimpleSearchService;
use Xsga\FilmAffinityApi\Modules\Shared\Api\Infrastructure\Controllers\AbstractController;

final class SimpleSearchController extends AbstractController
{
    public function __construct(private SimpleSearchService $simpleSearchService)
    {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $this->validateJson((string)$request->getBody(), 'simple.search.schema');

        $body = $request->getParsedBody();

        $searchDto = new SearchDto();
        $searchDto->searchText = $body['text'];

        return $this->writeResponse($response, $this->simpleSearchService->search($searchDto));
    }
}
