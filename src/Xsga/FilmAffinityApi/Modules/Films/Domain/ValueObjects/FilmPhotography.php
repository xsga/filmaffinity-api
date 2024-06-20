<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects;

final class FilmPhotography
{
    protected string $value;

    public function __construct(string $photogrphy)
    {
        $this->value = $photogrphy;
    }

    public function value(): string
    {
        return $this->value;
    }
}
