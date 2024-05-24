<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Services;

interface AuthSecurityService
{
    public function apply(string $userEmail, string $route): bool;
}
