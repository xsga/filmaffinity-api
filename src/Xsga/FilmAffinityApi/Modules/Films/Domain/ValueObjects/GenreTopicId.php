<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects;

final class GenreTopicId
{
    private int $value;

    public function __construct(int $id)
    {
        $this->value = $id;
    }

    public function value(): int
    {
        return $this->value;
    }
}
