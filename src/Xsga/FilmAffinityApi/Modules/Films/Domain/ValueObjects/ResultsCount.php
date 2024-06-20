<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects;

final class ResultsCount
{
    private int $value;

    public function __construct(int $count)
    {
        $this->value = $count;
    }

    public function value(): int
    {
        return $this->value;
    }
}
