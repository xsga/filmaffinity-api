<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\AdvancedSearchDto;
use Xsga\FilmAffinityApi\Modules\Films\Application\Services\AdvancedSearchService;
use Xsga\FilmAffinityApi\Modules\Shared\Api\Infrastructure\Controllers\AbstractController;

final class AdvancedSearchController extends AbstractController
{
    public function __construct(private AdvancedSearchService $advancedSearchService)
    {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $this->validateJson((string)$request->getBody(), 'advanced.search.schema');

        $body = $request->getParsedBody();

        // TODO: mapper.
        $searchDto = new AdvancedSearchDto();

        $searchDto->searchText            = $body['text'];
        $searchDto->searchTypeTitle       = $body['title'] ?? false;
        $searchDto->searchTypeDirector    = $body['director'] ?? false;
        $searchDto->searchTypeCast        = $body['cast'] ?? false;
        $searchDto->searchTypeScreenplay  = $body['screenplay'] ?? false;
        $searchDto->searchTypePhotography = $body['photography'] ?? false;
        $searchDto->searchTypeSoundtrack  = $body['soundtrack'] ?? false;
        $searchDto->searchTypeProducer    = $body['producer'] ?? false;
        $searchDto->searchGenre           = $body['genre'] ?? '';
        $searchDto->searchCountry         = $body['country'] ?? '';
        $searchDto->searchYearFrom        = $body['year_from'] ?? 0;
        $searchDto->searchYearTo          = $body['year_to'] ?? 0;

        return $this->writeResponse($response, $this->advancedSearchService->search($searchDto));
    }
}
