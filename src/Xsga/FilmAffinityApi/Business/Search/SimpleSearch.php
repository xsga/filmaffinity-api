<?php

/**
 * SimpleSearch.
 *
 * PHP Version 8
 *
 * @author  xsga <parker@xsga.es>
 * @license MIT
 * @version 1.0.0
 */

/**
 * Namespace.
 */
namespace Xsga\FilmAffinityApi\Business\Search;

/**
 * Import dependencies.
 */
use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Business\Extractor\Extractor;
use Xsga\FilmAffinityApi\Business\Parser\SimpleSearchParser;
use Xsga\FilmAffinityApi\Dto\SearchDto;
use Xsga\FilmAffinityApi\Dto\SearchResultsDto;

/**
 * Class SimpleSearch.
 */
final class SimpleSearch
{
    /**
     * Constructor.
     */
    public function __construct(
        private LoggerInterface $logger,
        private string $searchUrl,
        private Extractor $extractor,
        private SimpleSearchParser $parser
    ) {
    }

    /**
     * Search.
     */
    public function search(SearchDto $searchDto): SearchResultsDto
    {
        // Get page content.
        $pageContent = $this->extractor->getData($this->getSearchUrl($searchDto));

        // Inits parser.
        $this->parser->init($pageContent);

        // Get search results DTO.
        $out = $this->parser->getSimpleSearchResultsDto();

        return $out;
    }

     /**
     * Get search URL.
     */
    private function getSearchUrl(SearchDto $searchDto): string
    {
        // Get url.
        $urlSearch = str_replace(
            '{1}',
            $this->prepareSearchText($searchDto->searchText),
            $this->searchUrl
        );

        $this->logger->debug('Search URL: ' . $urlSearch);

        return $urlSearch;
    }

    /**
     * Prepare search text.
     */
    private function prepareSearchText(string $searchText): string
    {
        // Prepare search string.
        $searchText = trim($searchText);
        $searchText = str_replace(' ', '+', $searchText);

        return $searchText;
    }
}
