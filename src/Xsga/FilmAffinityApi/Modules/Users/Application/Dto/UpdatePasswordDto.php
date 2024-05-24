<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Application\Dto;

final class UpdatePasswordDto
{
    public int $userId = 0;
    public string $oldPassword = '';
    public string $newPassword = '';
}
