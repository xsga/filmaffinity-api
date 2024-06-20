<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Xsga\FilmAffinityApi\Modules\Films\Application\Services\AdvancedSearchService;
use Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Mappers\JsonAdvancedSearchToAdvancedSearchDto;
use Xsga\FilmAffinityApi\Modules\Shared\Api\Infrastructure\Controllers\AbstractController;

final class AdvancedSearchController extends AbstractController
{
    private string $schema = 'advanced.search.schema';

    public function __construct(
        private AdvancedSearchService $advancedSearchService,
        private JsonAdvancedSearchToAdvancedSearchDto $inputToAdvancedSearchDto
    ) {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $this->validateJson((string)$request->getBody(), $this->schema);

        $advancedSearchDto = $this->inputToAdvancedSearchDto->convert($request->getParsedBody());

        return $this->writeResponse($response, $this->advancedSearchService->search($advancedSearchDto));
    }
}
