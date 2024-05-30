<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Application\Services;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\SearchDto;
use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\SearchResultsDto;
use Xsga\FilmAffinityApi\Modules\Films\Application\Mappers\SearchResultsToSearchResultsDto;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers\SimpleSearchParser;
use Xsga\FilmAffinityApi\Modules\Shared\HttpClient\Application\Services\HttpClientService;

final class SimpleSearchService
{
    public function __construct(
        private LoggerInterface $logger,
        private string $searchUrl,
        private HttpClientService $httpClientService,
        private SimpleSearchParser $parser,
        private SearchResultsToSearchResultsDto $mapper
    ) {
    }

    public function search(SearchDto $searchDto): SearchResultsDto
    {
        $pageContent = $this->httpClientService->getPageContent($this->getSearchUrl($searchDto));

        $this->parser->init($pageContent);

        $searchResults = $this->parser->getSimpleSearchResultsDto();

        return $this->mapper->convert($searchResults);
    }

    private function getSearchUrl(SearchDto $searchDto): string
    {
        $urlSearch = str_replace(
            '{1}',
            $this->prepareSearchText($searchDto->searchText),
            $this->searchUrl
        );

        $this->logger->debug('Search URL: ' . $urlSearch);

        return $urlSearch;
    }

    private function prepareSearchText(string $searchText): string
    {
        $searchText = trim($searchText);
        $searchText = str_replace(' ', '+', $searchText);

        return $searchText;
    }
}