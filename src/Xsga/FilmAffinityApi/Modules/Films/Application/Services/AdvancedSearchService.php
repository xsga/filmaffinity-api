<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Application\Services;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\AdvancedSearchDto;
use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\SearchResultsDto;
use Xsga\FilmAffinityApi\Modules\Films\Application\Mappers\AdvSearchDtoToAdvSearch;
use Xsga\FilmAffinityApi\Modules\Films\Application\Mappers\SearchResultsToSearchResultsDto;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Exceptions\CountryNotFoundException;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Exceptions\GenreNotFoundException;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Exceptions\InvalidSearchLengthException;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers\AdvancedSearchParser;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories\AdvancedSearchRepository;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories\CountriesRepository;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories\GenresRepository;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Services\UrlService;
use Xsga\FilmAffinityApi\Modules\Shared\HttpClient\Application\Services\HttpClientService;

final class AdvancedSearchService
{
    public function __construct(
        private LoggerInterface $logger,
        private HttpClientService $httpClientService,
        private AdvancedSearchParser $parser,
        private GenresRepository $genresRepository,
        private CountriesRepository $countriesRepository,
        private SearchResultsToSearchResultsDto $mapper,
        private UrlService $urlService,
        private AdvSearchDtoToAdvSearch $advSearchMapper,
        private AdvancedSearchRepository $repository
    ) {
    }

    public function search(AdvancedSearchDto $advancedSearchDto): SearchResultsDto
    {
        $this->validatesSearchTextLength($advancedSearchDto->searchText);
        $this->validatesGenre($advancedSearchDto->searchGenre);
        $this->validatesCountry($advancedSearchDto->searchCountry);

        $searchResults = $this->repository->get($this->advSearchMapper->convert($advancedSearchDto));

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
}
