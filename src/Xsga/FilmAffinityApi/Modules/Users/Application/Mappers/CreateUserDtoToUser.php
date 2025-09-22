<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Application\Mappers;

use DateTime;
use Xsga\FilmAffinityApi\Modules\Users\Application\Dto\CreateUserDto;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Model\User;

final class CreateUserDtoToUser
{
    public function convert(CreateUserDto $userData): User
    {
        return new User(
            0,
            $userData->email,
            $userData->password,
            true,
            true,
            new DateTime(),
            new DateTime()
        );
    }
}
