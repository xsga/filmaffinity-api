<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Mappers;

use Xsga\FilmAffinityApi\Modules\Users\Application\Dto\UpdatePasswordDto;

final class JsonInputToUpdatePasswordDto
{
    public function convert(int $userId, string $reqUserEmail, array $userData): UpdatePasswordDto
    {
        $updatePasswordDto = new UpdatePasswordDto();

        $updatePasswordDto->userId       = $userId;
        $updatePasswordDto->oldPassword  = (string)$userData['oldPassword'];
        $updatePasswordDto->newPassword  = (string)$userData['newPassword'];

        return $updatePasswordDto;
    }
}
