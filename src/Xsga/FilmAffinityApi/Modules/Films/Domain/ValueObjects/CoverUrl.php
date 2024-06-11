<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects;

final class CoverUrl
{
    protected string $value;

    public function __construct(string $url)
    {
        $this->value = $url;
    }

    public function value(): string
    {
        return $this->value;
    }
}
