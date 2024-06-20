<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects;

final class FilmSynopsis
{
    protected string $value;

    public function __construct(string $synopsis)
    {
        $this->value = $synopsis;
    }

    public function value(): string
    {
        return $this->value;
    }
}
