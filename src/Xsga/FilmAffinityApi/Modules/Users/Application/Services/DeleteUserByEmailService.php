<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Application\Services;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Exceptions\DeleteUserException;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Repositories\UsersRepository;

final class DeleteUserByEmailService
{
    public function __construct(
        private LoggerInterface $logger,
        private UsersRepository $usersRepository
    ) {
    }

    public function delete(string $userEmail): bool
    {
        $userDeleteStatus = $this->usersRepository->deleteUserByEmail($userEmail);
        $this->validateUserDelete($userDeleteStatus, $userEmail);

        return true;
    }

    private function validateUserDelete(bool $userDeleteStatus, string $userEmail): void
    {
        if (!$userDeleteStatus) {
            $message = "Error deleting user";
            $this->logger->error($message);
            throw new DeleteUserException($message, 1042);
        }

        $this->logger->info("User '$userEmail' deleted successfully");
    }
}
