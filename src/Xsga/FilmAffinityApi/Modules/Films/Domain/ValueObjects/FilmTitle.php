<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects;

final class FilmTitle
{
    protected string $value;

    public function __construct(string $title)
    {
        $this->value = $title;
    }

    public function value(): string
    {
        return $this->value;
    }
}
