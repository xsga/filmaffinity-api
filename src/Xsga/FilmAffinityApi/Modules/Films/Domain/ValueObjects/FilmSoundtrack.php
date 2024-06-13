<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects;

final class FilmSoundtrack
{
    protected string $value;

    public function __construct(string $soundtrack)
    {
        $this->value = $soundtrack;
    }

    public function value(): string
    {
        return $this->value;
    }
}
