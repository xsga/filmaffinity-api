<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Business\Users;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Repositories\UsersRepositoryInterface;

final class ChangeUserStatus
{
    public function __construct(
        private LoggerInterface $logger,
        private UsersRepositoryInterface $repository
    ) {
    }

    public function change(string $userEmail, int $status): bool
    {
        if ($status !== 0 && $status !== 1) {
            $this->logger->error("Status \"$status\" not valid");
            return false;
        }

        $userEntity = $this->repository->getUser($userEmail);

        if (empty($userEntity)) {
            $this->logger->error("User \"$userEmail\" not found");
            return false;
        }

        $userEntity->setEnabled($status);

        if (!$this->repository->updateUser($userEntity)) {
            $this->logger->error('Error changing user status');
            return false;
        }

        return true;
    }
}
