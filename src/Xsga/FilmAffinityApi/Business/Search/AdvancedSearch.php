<?php

/**
 * AdvancedSearch.
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
use Xsga\FilmAffinityApi\Business\Countries\Countries;
use Xsga\FilmAffinityApi\Business\Extractor\Extractor;
use Xsga\FilmAffinityApi\Business\Genres\Genres;
use Xsga\FilmAffinityApi\Business\Parser\AdvancedSearchParser;
use Xsga\FilmAffinityApi\Dto\AdvSearchDto;
use Xsga\FilmAffinityApi\Dto\SearchResultsDto;
use Xsga\FilmAffinityApi\Exceptions\CountryNotFoundException;
use Xsga\FilmAffinityApi\Exceptions\GenreNotFoundException;
use Xsga\FilmAffinityApi\Exceptions\InvalidLengthException;

/**
 * Class AdvancedSearch.
 */
final class AdvancedSearch
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
     * Advanced search URL.
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
     * @var AdvancedSearchParser
     *
     * @access private
     */
    private $parser;

    /**
     * Genres.
     *
     * @var Genres
     *
     * @access private
     */
    private $genres;

    /**
     * Countries.
     *
     * @var Countries
     *
     * @access private
     */
    private $countries;

    /**
     * Constructor.
     *
     * @param LoggerInterface      $logger    LoggerInterface instance.
     * @param string               $searchUrl Advanced search URL.
     * @param Extractor            $extractor Extractor instance.
     * @param AdvancedSearchParser $parser    AdvancedSearchParser instance.
     * @param Genres               $genres    Genres instance.
     * @param Countries            $contries  Countries instance.
     *
     * @access public
     */
    public function __construct(
        LoggerInterface $logger,
        string $searchUrl,
        Extractor $extractor,
        AdvancedSearchParser $parser,
        Genres $genres,
        Countries $countries
    ) {
        $this->logger    = $logger;
        $this->searchUrl = $searchUrl;
        $this->extractor = $extractor;
        $this->parser    = $parser;
        $this->genres    = $genres;
        $this->countries = $countries;
    }

    /**
     * Advanced search.
     *
     * @param AdvSearchDto $advSearchDto Advanced search data.
     *
     * @return SearchResultsDto
     *
     * @access public
     */
    public function search(AdvSearchDto $advSearchDto): SearchResultsDto
    {
        // Validations.
        $this->validations($advSearchDto);

        // Get page content.
        $pageContent = $this->extractor->getData($this->getUrl($advSearchDto));

        // Inits parser.
        $this->parser->init($pageContent);

        // Get search results DTO.
        $out = $this->parser->getAdvSearchResultsDto();

        return $out;
    }

    /**
     * Validates input data.
     *
     * @param AdvSearchDto $advSearchDto Advanced search DTO.
     *
     * @return void
     *
     * @access private
     */
    private function validations(AdvSearchDto $advSearchDto): void
    {
        if (strlen($advSearchDto->searchText) < 3) {
            $errorMsg = 'Search text lenght not valid';
            $this->logger->error($errorMsg);
            throw new InvalidLengthException($errorMsg, 1008);
        }//end if

        if (!empty($advSearchDto->searchGenre)) {
            $genre = $this->genres->get($advSearchDto->searchGenre);

            if ($genre->code === '') {
                $errorMsg = 'Genre code not valid';
                $this->logger->error($errorMsg);
                throw new GenreNotFoundException($errorMsg, 1009);
            }//end if
        }//end if

        if (!empty($advSearchDto->searchCountry)) {
            $country = $this->countries->get($advSearchDto->searchCountry);

            if ($country->code === '') {
                $errorMsg = 'Country code not valid';
                $this->logger->error($errorMsg);
                throw new CountryNotFoundException($errorMsg, 1010);
            }//end if
        }//end if
    }

    /**
     * Get advanced search URL.
     *
     * @param AdvSearchDto $advSearchDto Advanced search DTO.
     *
     * @return string
     *
     * @access private
     */
    private function getUrl(AdvSearchDto $advSearchDto): string
    {
        // Prepare search type.
        $searchType  = '';
        $searchType .= $advSearchDto->searchTypeTitle ? '&stype[]=title' : '';
        $searchType .= $advSearchDto->searchTypeDirector ? '&stype[]=director' : '';
        $searchType .= $advSearchDto->searchTypeCast ? '&stype[]=cast' : '';
        $searchType .= $advSearchDto->searchTypeSoundtrack ? '&stype[]=music' : '';
        $searchType .= $advSearchDto->searchTypeScreenplay ? '&stype[]=script' : '';
        $searchType .= $advSearchDto->searchTypePhotography ? '&stype[]=photo' : '';
        $searchType .= $advSearchDto->searchTypeProducer ? '&stype[]=producer' : '';

        // By default, search in title.
        if ($searchType === '') {
            $this->logger->warning('No search type found. Set the default search type: title');
            $searchType = '&stype[]=title';
        }//end if

        // Get url.
        $url = $this->searchUrl;
        $url = str_replace('{1}', $this->prepareSearchText($advSearchDto->searchText), $url);
        $url = str_replace('{2}', $searchType, $url);
        $url = str_replace('{3}', $advSearchDto->searchCountry, $url);
        $url = str_replace('{4}', $advSearchDto->searchGenre, $url);
        $url = str_replace('{5}', $advSearchDto->searchYearFrom === 0 ? '' : $advSearchDto->searchYearFrom, $url);
        $url = str_replace('{6}', $advSearchDto->searchYearTo === 0 ? '' : $advSearchDto->searchYearTo, $url);

        $this->logger->debug('Advanced Search URL: ' . $url);

        return $url;
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
