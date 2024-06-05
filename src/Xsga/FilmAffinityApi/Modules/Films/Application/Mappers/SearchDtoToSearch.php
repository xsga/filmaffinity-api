<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Application\Mappers;

use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\SearchDto;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Search;

class SearchDtoToSearch
{
    public function convert(SearchDto $searchDto): Search
    {
        $search = new Search();

        $search->searchText = $searchDto->searchText;

        return $search;
    }
}
