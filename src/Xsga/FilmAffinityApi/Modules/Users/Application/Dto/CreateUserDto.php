<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Application\Dto;

final class CreateUserDto
{
    public string $email = '';
    public string $password = '';
    public string $name = '';
}
