<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Business\Search;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\App\Application\Dto\SearchDto;

final class SimpleSearch
{
    public function __construct(
        private LoggerInterface $logger,
        private string $searchUrl,
        private Extractor $extractor,
        private SimpleSearchParser $parser
    ) {
    }

    public function search(SearchDto $searchDto): SearchResultsDto
    {
        $pageContent = $this->extractor->getData($this->getSearchUrl($searchDto));

        $this->parser->init($pageContent);

        $out = $this->parser->getSimpleSearchResultsDto();

        return $out;
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
