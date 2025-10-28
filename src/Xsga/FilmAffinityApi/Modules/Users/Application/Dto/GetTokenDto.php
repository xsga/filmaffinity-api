<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Application\Dto;

final class GetTokenDto
{
    public string $user = '';
    public string $password = '';
}
