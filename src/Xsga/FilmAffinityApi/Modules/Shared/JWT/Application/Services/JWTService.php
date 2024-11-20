<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\JWT\Application\Services;

use Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Dto\UserDataTokenDto;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Model\User;

interface JWTService
{
    public function get(User $user): string;
    public function decode(string $token): ?UserDataTokenDto;
}
