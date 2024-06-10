<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Mappers;

use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\SearchDto;

final class JsonSimpleSearchToSearchDto
{
    public function convert(array $jsonData): SearchDto
    {
        $searchDto = new SearchDto();

        $searchDto->searchText = $jsonData['text'];

        return $searchDto;
    }
}
