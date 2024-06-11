<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects;

final class CoverFileName
{
    protected string $value;

    public function __construct(string $fileName)
    {
        $this->value = $fileName;
    }

    public function value(): string
    {
        return $this->value;
    }
}
