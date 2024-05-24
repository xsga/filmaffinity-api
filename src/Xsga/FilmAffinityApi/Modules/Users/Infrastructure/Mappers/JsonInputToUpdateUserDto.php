<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Mappers;

use Xsga\FilmAffinityApi\Modules\Users\Application\Dto\UpdateUserDto;

final class JsonInputToUpdateUserDto
{
    public function convert(int $userId, array $userData): UpdateUserDto
    {
        $user = new UpdateUserDto();

        $user->userId       = $userId;
        $user->name         = $userData['name'];

        return $user;
    }
}
