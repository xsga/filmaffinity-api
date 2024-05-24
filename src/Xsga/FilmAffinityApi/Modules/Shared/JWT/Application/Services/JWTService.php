<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\JWT\Application\Services;

use Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Dto\UserDataTokenDto;
use Xsga\FilmAffinityApi\Modules\Users\Application\Dto\UserDto;

interface JWTService
{
    public function get(UserDto $userDto): string;
    public function decode(string $token): ?UserDataTokenDto;
}
