<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Mappers;

use Xsga\FilmAffinityApi\Modules\Users\Application\Dto\CreateUserDto;

final class JsonInputToCreateUserDto
{
    public function convert(array $userData): CreateUserDto
    {
        $user = new CreateUserDto();

        $user->email = match (isset($userData['email'])) {
            true => $userData['email'],
            false => ''
        };

        $user->password = match (isset($userData['password'])) {
            true => $userData['password'],
            false => ''
        };

        return $user;
    }
}
