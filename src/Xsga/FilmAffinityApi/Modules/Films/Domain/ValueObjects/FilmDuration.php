<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects;

final class FilmDuration
{
    private int $value;

    public function __construct(int $duration)
    {
        $this->value = $duration;
    }

    public function value(): int
    {
        return $this->value;
    }
}
