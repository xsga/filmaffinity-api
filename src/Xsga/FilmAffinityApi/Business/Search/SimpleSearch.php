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
     * Logger.
     *
     * @var LoggerInterface
     *
     * @access private
     */
    private $logger;

    /**
     * Search URL.
     *
     * @var string
     *
     * @access private
     */
    private $searchUrl;

    /**
     * Extractor.
     *
     * @var Extractor
     *
     * @access private
     */
    private $extractor;

    /**
     * Parser.
     *
     * @var SimpleSearchParser
     *
     * @access private
     */
    private $parser;

    /**
     * Constructor.
     *
     * @param LoggerInterface    $logger    LoggerInterface instance.
     * @param string             $searchUrl Simple search URL.
     * @param Extractor          $extractor Extractor instance.
     * @param SimpleSearchParser $parser    SimpleSearchParser instance.
     *
     * @access public
     */
    public function __construct(
        LoggerInterface $logger,
        string $searchUrl,
        Extractor $extractor,
        SimpleSearchParser $parser
    ) {
        $this->logger    = $logger;
        $this->searchUrl = $searchUrl;
        $this->extractor = $extractor;
        $this->parser    = $parser;
    }

    /**
     * Search.
     *
     * @param SearchDto $searchDto Search DTO.
     *
     * @return SearchResultsDto
     *
     * @access public
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
     *
     * @param SearchDto $searchDto Search DTO.
     *
     * @return string
     *
     * @access private
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
     *
     * @param string $searchText Text to search.
     *
     * @return string
     *
     * @access private
     */
    private function prepareSearchText(string $searchText): string
    {
        // Prepare search string.
        $searchText = trim($searchText);
        $searchText = str_replace(' ', '+', $searchText);

        return $searchText;
    }
}
