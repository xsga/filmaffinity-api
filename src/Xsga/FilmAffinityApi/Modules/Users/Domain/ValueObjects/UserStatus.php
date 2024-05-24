<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Domain\ValueObjects;

final class UserStatus
{
    private readonly bool $userStatus;

    public function __construct(bool $userStatus)
    {
        $this->userStatus = $userStatus;
    }

    public function value(): bool
    {
        return $this->userStatus;
    }
}
