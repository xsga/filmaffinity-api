<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects;

final class FilmRating
{
    protected string $value;

    public function __construct(string $rating)
    {
        $this->value = $rating;
    }

    public function value(): string
    {
        return $this->value;
    }
}
