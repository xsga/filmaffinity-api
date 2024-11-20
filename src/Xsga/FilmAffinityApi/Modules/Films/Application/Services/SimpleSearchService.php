<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Application\Services;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\SearchDto;
use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\SearchResultsDto;
use Xsga\FilmAffinityApi\Modules\Films\Application\Mappers\SearchDtoToSearch;
use Xsga\FilmAffinityApi\Modules\Films\Application\Mappers\SearchResultsToSearchResultsDto;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Exceptions\InvalidSearchLengthException;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories\SearchRepository;

final class SimpleSearchService
{
    private const int SEARCH_TEXT_MIN_LENGTH = 2;

    public function __construct(
        private LoggerInterface $logger,
        private SearchResultsToSearchResultsDto $mapper,
        private SearchRepository $repository,
        private SearchDtoToSearch $searchMapper
    ) {
    }

    public function search(SearchDto $searchDto): SearchResultsDto
    {
        $this->validatesSearchTextLength($searchDto->searchText);

        $searchResults = $this->repository->get($this->searchMapper->convert($searchDto));

        $this->logger->info('The search returned ' . $searchResults->total() . ' results');

        return $this->mapper->convert($searchResults);
    }

    private function validatesSearchTextLength(string $searchText): void
    {
        if (strlen($searchText) < self::SEARCH_TEXT_MIN_LENGTH) {
            $errorMsg = 'Search text length not valid';
            $this->logger->error($errorMsg);
            throw new InvalidSearchLengthException($errorMsg, 2001);
        }
    }
}
