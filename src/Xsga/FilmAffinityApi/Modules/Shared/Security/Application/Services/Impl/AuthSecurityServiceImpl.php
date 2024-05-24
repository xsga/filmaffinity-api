<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Services\Impl;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Services\AuthSecurityService;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Services\GetUser;

final class AuthSecurityServiceImpl implements AuthSecurityService
{
    public function __construct(
        private LoggerInterface $logger,
        private GetUser $getUser
    ) {
    }

    public function apply(string $userEmail, string $route): bool
    {
        $user = $this->getUser->byEmail($userEmail);

        if (array_search($route, $user->getRoutes()) !== false) {
            $this->logger->debug('User allowed to access');
            return true;
        };

        $this->logger->debug('User not allowed to access');
        return false;
    }
}
