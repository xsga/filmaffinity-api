<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Application\Services;

use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\SearchDto;
use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\SearchResultsDto;
use Xsga\FilmAffinityApi\Modules\Films\Application\Mappers\SearchDtoToSearch;
use Xsga\FilmAffinityApi\Modules\Films\Application\Mappers\SearchResultsToSearchResultsDto;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories\SearchRepository;

final class SimpleSearchService
{
    public function __construct(
        private SearchResultsToSearchResultsDto $mapper,
        private SearchRepository $repository,
        private SearchDtoToSearch $searchMapper
    ) {
    }

    public function search(SearchDto $searchDto): SearchResultsDto
    {
        $searchResults = $this->repository->get($this->searchMapper->convert($searchDto));

        return $this->mapper->convert($searchResults);
    }
}
