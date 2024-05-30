<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Application\Services;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\SearchDto;
use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\SearchResultsDto;
use Xsga\FilmAffinityApi\Modules\Films\Application\Mappers\SearchResultsToSearchResultsDto;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers\SimpleSearchParser;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Services\UrlService;
use Xsga\FilmAffinityApi\Modules\Shared\HttpClient\Application\Services\HttpClientService;

final class SimpleSearchService
{
    public function __construct(
        private LoggerInterface $logger,
        private HttpClientService $httpClientService,
        private SimpleSearchParser $parser,
        private SearchResultsToSearchResultsDto $mapper,
        private UrlService $urlService
    ) {
    }

    public function search(SearchDto $searchDto): SearchResultsDto
    {
        $searchUrl   = $this->urlService->getSearchUrl($searchDto);
        $pageContent = $this->httpClientService->getPageContent($searchUrl);

        $this->parser->init($pageContent);

        $searchResults = $this->parser->getSimpleSearchResultsDto();

        return $this->mapper->convert($searchResults);
    }
}
