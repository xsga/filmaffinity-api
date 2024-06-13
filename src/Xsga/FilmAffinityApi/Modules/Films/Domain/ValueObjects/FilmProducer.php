<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects;

final class FilmProducer
{
    protected string $value;

    public function __construct(string $producer)
    {
        $this->value = $producer;
    }

    public function value(): string
    {
        return $this->value;
    }
}
