<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Mappers\Impl;

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

        $user->name = match (isset($userData['name'])) {
            true => $userData['name'],
            false => ''
        };

        return $user;
    }
}
