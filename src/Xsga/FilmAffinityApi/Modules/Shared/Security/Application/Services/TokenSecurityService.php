<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Services;

use Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Dto\UserDataTokenDto;

interface TokenSecurityService
{
    public function apply(string $authHeader): ?UserDataTokenDto;
}
