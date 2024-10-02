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
    private const int MAX_LENGHT = 3;

    public function __construct(
        private LoggerInterface $logger,
        private GenresRepository $genresRepository,
        private CountriesRepository $countriesRepository,
        private SearchResultsToSearchResultsDto $mapper,
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
        if (strlen($searchText) < self::MAX_LENGHT) {
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

        if (is_null($this->genresRepository->get($searchGenre))) {
            $errorMsg = "Genre code '$searchGenre' not valid";
            $this->logger->error($errorMsg);
            throw new GenreNotFoundException($errorMsg, 2002, null, [1 => $searchGenre]);
        }
    }

    private function validatesCountry(string $searchCountry): void
    {
        if (empty($searchCountry)) {
            return;
        }

        if (is_null($this->countriesRepository->get($searchCountry))) {
            $errorMsg = "Country code '$searchCountry' not valid";
            $this->logger->error($errorMsg);
            throw new CountryNotFoundException($errorMsg, 2003, null, [1 => $searchCountry]);
        }
    }
}
