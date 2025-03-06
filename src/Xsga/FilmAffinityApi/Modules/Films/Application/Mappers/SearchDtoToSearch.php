<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Application\Mappers;

use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\SearchDto;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Search;

final class SearchDtoToSearch
{
    public function convert(SearchDto $searchDto): Search
    {
        return new Search($searchDto->searchText);
    }
}
