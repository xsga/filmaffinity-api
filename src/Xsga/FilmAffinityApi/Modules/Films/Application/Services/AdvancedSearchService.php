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
use Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories\AdvancedSearchRepository;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories\CountriesRepository;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories\GenresRepository;

final class AdvancedSearchService
{
    private const int SEARCH_TEXT_MIN_LENGTH = 3;

    public function __construct(
        private LoggerInterface $logger,
        private GenresRepository $genresRepository,
        private CountriesRepository $countriesRepository,
        private SearchResultsToSearchResultsDto $searchResultsMapper,
        private AdvSearchDtoToAdvSearch $advSearchMapper,
        private AdvancedSearchRepository $repository
    ) {
    }

    public function search(AdvancedSearchDto $advancedSearchDto): SearchResultsDto
    {
        $this->validatesSearchTextLength($advancedSearchDto->searchText);
        $this->validatesGenre($advancedSearchDto->searchGenreCode);
        $this->validatesCountry($advancedSearchDto->searchCountryCode);

        $searchResults = $this->repository->get($this->advSearchMapper->convert($advancedSearchDto));

        return $this->searchResultsMapper->convert($searchResults);
    }

    private function validatesSearchTextLength(string $searchText): void
    {
        if (strlen($searchText) < self::SEARCH_TEXT_MIN_LENGTH) {
            $errorMsg = 'Search text lenght not valid';
            $this->logger->error($errorMsg);
            throw new InvalidSearchLengthException($errorMsg, 2001);
        }
    }

    private function validatesGenre(string $searchGenreCode): void
    {
        if (empty($searchGenreCode)) {
            return;
        }

        if (is_null($this->genresRepository->get($searchGenreCode))) {
            $errorMsg = "Genre with code '$searchGenreCode' not valid";
            $this->logger->error($errorMsg);
            throw new GenreNotFoundException($errorMsg, 2002, null, [1 => $searchGenreCode]);
        }
    }

    private function validatesCountry(string $searchCountryCode): void
    {
        if (empty($searchCountryCode)) {
            return;
        }

        if (is_null($this->countriesRepository->get($searchCountryCode))) {
            $errorMsg = "Country with code '$searchCountryCode' not valid";
            $this->logger->error($errorMsg);
            throw new CountryNotFoundException($errorMsg, 2003, null, [1 => $searchCountryCode]);
        }
    }
}
