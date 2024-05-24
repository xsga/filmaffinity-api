<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Dto;

final class UserDataTokenDto
{
    public int $userId = 0;
    public string $email = '';
    public string $password = '';
}
