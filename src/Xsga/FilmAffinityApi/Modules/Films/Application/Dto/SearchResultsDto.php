<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Application\Dto;

final class SearchResultsDto
{
    public int $total = 0;

    /**
     * @var SingleSearchResultDto[]
     */
    public array $results = [];
}
