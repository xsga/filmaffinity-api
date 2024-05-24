<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Dto;

class SearchResultsDto
{
    public int $total = 0;

    /**
     * @var SingleSearchResultDto[]
     */
    public array $results = [];
}
