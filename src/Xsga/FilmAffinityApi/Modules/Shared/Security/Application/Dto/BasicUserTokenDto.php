<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Dto;

final class BasicUserTokenDto
{
    public string $name = '';
    public string $password = '';
}
