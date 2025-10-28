<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Mappers;

use Xsga\FilmAffinityApi\Modules\Users\Application\Dto\CreateUserDto;

final class JsonInputToCreateUserDto
{
    public function convert(array $userData): CreateUserDto
    {
        $user = new CreateUserDto();

        $user->email    = (string)$userData['email'];
        $user->password = (string)$userData['password'];

        return $user;
    }
}
