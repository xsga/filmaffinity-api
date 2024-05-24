<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Application\Dto;

class UserDto
{
    public int $userId = 0;
    public string $email = '';
    public string $hashedPass = '';
    public string $name = '';
    public bool $status = true;
    public string $createDate = '';
    public string $updateDate = '';
}
