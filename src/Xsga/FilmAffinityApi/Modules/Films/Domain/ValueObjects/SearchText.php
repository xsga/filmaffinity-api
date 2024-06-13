<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects;

final class SearchText
{
    protected string $value;

    public function __construct(string $text)
    {
        $this->value = $text;
    }

    public function value(): string
    {
        return $this->value;
    }
}
