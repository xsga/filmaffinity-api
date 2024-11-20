<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Model;

use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\ResultsCount;

final class SearchResults
{
    private ResultsCount $total;

    /**
     * @var SingleSearchResult[]
     */
    private array $results = [];

    /**
     * @param SingleSearchResult[] $results
     */
    public function __construct(int $total, array $results)
    {
        $this->total   = new ResultsCount($total);
        $this->results = $results;
    }

    public function total(): int
    {
        return $this->total->value();
    }

    /**
     * @return SingleSearchResult[]
     */
    public function results(): array
    {
        return $this->results;
    }
}
