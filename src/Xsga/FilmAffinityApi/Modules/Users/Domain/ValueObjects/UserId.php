<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Domain\ValueObjects;

final class UserId
{
    private readonly int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function value(): int
    {
        return $this->userId;
    }
}
