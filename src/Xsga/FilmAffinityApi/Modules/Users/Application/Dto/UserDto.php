<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Application\Dto;

final class UserDto
{
    public int $userId = 0;
    public string $email = '';
    public bool $status = true;
    public string $createDate = '';
    public string $updateDate = '';
}
