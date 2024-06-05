<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Model;

class SearchResults
{
    public int $total = 0;

    /**
     * @var SingleSearchResult[]
     */
    public array $results = [];
}
