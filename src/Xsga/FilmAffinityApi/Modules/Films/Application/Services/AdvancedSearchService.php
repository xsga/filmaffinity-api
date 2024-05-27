<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Application\Services;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\AdvancedSearchDto;
use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\SearchResultsDto;
use Xsga\FilmAffinityApi\Modules\Films\Application\Mappers\SearchResultsToSearchResultsDto;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Exceptions\CountryNotFoundException;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Exceptions\GenreNotFoundException;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Exceptions\InvalidSearchLengthException;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Parser\AdvancedSearchParser;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories\CountriesRepository;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories\GenresRepository;
use Xsga\FilmAffinityApi\Modules\Shared\HttpClient\Application\Services\HttpClientService;

final class AdvancedSearchService
{
    public function __construct(
        private LoggerInterface $logger,
        private string $searchUrl,
        private HttpClientService $httpClientService,
        private AdvancedSearchParser $parser,
        private GenresRepository $genresRepository,
        private CountriesRepository $countriesRepository,
        private SearchResultsToSearchResultsDto $mapper
    ) {
    }

    public function search(AdvancedSearchDto $advancedSearchDto): SearchResultsDto
    {
        $this->validatesSearchTextLength($advancedSearchDto->searchText);
        $this->validatesGenre($advancedSearchDto->searchGenre);
        $this->validatesCountry($advancedSearchDto->searchCountry);

        $pageContent = $this->httpClientService->getPageContent($this->getUrl($advancedSearchDto));

        $this->parser->init($pageContent);

        $searchResults = $this->parser->getAdvSearchResultsDto();

        return $this->mapper->convert($searchResults);
    }

    private function validatesSearchTextLength(string $searchText): void
    {
        if (strlen($searchText) < 3) {
            $errorMsg = 'Search text lenght not valid';
            $this->logger->error($errorMsg);
            throw new InvalidSearchLengthException($errorMsg, 2001);
        }
    }

    private function validatesGenre(string $searchGenre): void
    {
        if (empty($searchGenre)) {
            return;
        }

        $genre = $this->genresRepository->get($searchGenre);

        if (is_null($genre)) {
            $errorMsg = 'Genre code not valid';
            $this->logger->error($errorMsg);
            throw new GenreNotFoundException($errorMsg, 2002);
        }
    }

    private function validatesCountry(string $searchCountry): void
    {
        if (empty($searchCountry)) {
            return;
        }

        $country = $this->countriesRepository->get($searchCountry);

        if (is_null($country)) {
            $errorMsg = 'Country code not valid';
            $this->logger->error($errorMsg);
            throw new CountryNotFoundException($errorMsg, 2003);
        }
    }

    private function getUrl(AdvancedSearchDto $advancedSearchDto): string
    {
        $searchType  = '';
        $searchType .= $advancedSearchDto->searchTypeTitle ? '&stype[]=title' : '';
        $searchType .= $advancedSearchDto->searchTypeDirector ? '&stype[]=director' : '';
        $searchType .= $advancedSearchDto->searchTypeCast ? '&stype[]=cast' : '';
        $searchType .= $advancedSearchDto->searchTypeSoundtrack ? '&stype[]=music' : '';
        $searchType .= $advancedSearchDto->searchTypeScreenplay ? '&stype[]=script' : '';
        $searchType .= $advancedSearchDto->searchTypePhotography ? '&stype[]=photo' : '';
        $searchType .= $advancedSearchDto->searchTypeProducer ? '&stype[]=producer' : '';

        if ($searchType === '') {
            $this->logger->warning('No search type found. Set the default search type: title');
            $searchType = '&stype[]=title';
        }

        $url = $this->searchUrl;
        $url = str_replace('{1}', $this->prepareSearchText($advancedSearchDto->searchText), $url);
        $url = str_replace('{2}', $searchType, $url);
        $url = str_replace('{3}', $advancedSearchDto->searchCountry, $url);
        $url = str_replace('{4}', $advancedSearchDto->searchGenre, $url);
        $url = str_replace('{5}', $advancedSearchDto->searchYearFrom === 0 ? '' : $advancedSearchDto->searchYearFrom, $url);
        $url = str_replace('{6}', $advancedSearchDto->searchYearTo === 0 ? '' : $advancedSearchDto->searchYearTo, $url);

        $this->logger->debug('Advanced Search URL: ' . $url);

        return $url;
    }

    private function prepareSearchText(string $searchText): string
    {
        $searchText = trim($searchText);
        $searchText = str_replace(' ', '+', $searchText);

        return $searchText;
    }
}
