<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects;

final class FilmScreenplay
{
    protected string $value;

    public function __construct(string $screenplay)
    {
        $this->value = $screenplay;
    }

    public function value(): string
    {
        return $this->value;
    }
}
