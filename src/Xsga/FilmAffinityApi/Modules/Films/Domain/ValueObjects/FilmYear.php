<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects;

final class FilmYear
{
    private string $value;

    public function __construct(string $year)
    {
        $this->value = $year;
    }

    public function value(): string
    {
        return $this->value;
    }
}
