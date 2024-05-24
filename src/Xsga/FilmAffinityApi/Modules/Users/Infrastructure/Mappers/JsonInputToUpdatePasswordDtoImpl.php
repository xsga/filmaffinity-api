<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Mappers\Impl;

use Xsga\FilmAffinityApi\Modules\Users\Application\Dto\UpdatePasswordDto;

final class JsonInputToUpdatePasswordDto
{
    public function convert(int $userId, string $reqUserEmail, array $userData): UpdatePasswordDto
    {
        $updatePasswordDto = new UpdatePasswordDto();

        $updatePasswordDto->userId       = $userId;
        $updatePasswordDto->oldPassword  = $userData['oldPassword'];
        $updatePasswordDto->newPassword  = $userData['newPassword'];

        return $updatePasswordDto;
    }
}
