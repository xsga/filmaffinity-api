<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects;

final class FilmYear
{
    private int $value;

    public function __construct(int $year)
    {
        $this->value = $year;
    }

    public function value(): int
    {
        return $this->value;
    }
}
