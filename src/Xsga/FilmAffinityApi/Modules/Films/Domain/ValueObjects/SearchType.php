<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects;

final class SearchType
{
    protected bool $value;

    public function __construct(bool $type)
    {
        $this->value = $type;
    }

    public function value(): bool
    {
        return $this->value;
    }
}
