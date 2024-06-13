<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Application\Mappers;

use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\SearchResultsDto;
use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\SingleSearchResultDto;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\SearchResults;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\SingleSearchResult;

class SearchResultsToSearchResultsDto
{
    public function convert(SearchResults $searchResults): SearchResultsDto
    {
        $searchResultsDto = new SearchResultsDto();
        $searchResultsDto->total   = $searchResults->total;
        $searchResultsDto->results = $this->convertSingleSearchResult($searchResults->results);

        return $searchResultsDto;
    }

    /**
     * @param SingleSearchResult[] $singleSearchResults
     * 
     * @return SingleSearchResultDto[]
     */
    private function convertSingleSearchResult(array $singleSearchResults): array
    {
        $out = [];

        foreach ($singleSearchResults as $singleSearchResult) {
            $out[] = $this->getSingleSearchResultDto($singleSearchResult);
        }

        return $out;
    }

    private function getSingleSearchResultDto(SingleSearchResult $singleSearchResult): SingleSearchResultDto
    {
        $singleSearchResultDto = new SingleSearchResultDto();
        $singleSearchResultDto->id    = $singleSearchResult->id;
        $singleSearchResultDto->title = $singleSearchResult->title;

        return $singleSearchResultDto;
    }
}
