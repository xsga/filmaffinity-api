<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Application\Services;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Exceptions\UpdateUserException;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Model\User;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Repositories\UsersRepository;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Services\GetUser;

final class UpdateUserStatusService
{
    public function __construct(
        private LoggerInterface $logger,
        private UsersRepository $usersRepository,
        private GetUser $getUser
    ) {
    }

    public function set(int $userId, bool $newStatus): bool
    {
        $user = $this->getUser->byId($userId);

        $this->validateActualUserStatus($user, $newStatus);
        $this->changeUserStatus($user, $newStatus);

        $userUpdateStatus = $this->usersRepository->updateUserStatus($user);
        $this->validateUserUpdate($userUpdateStatus, $user->email());

        return true;
    }

    private function changeUserStatus(User $user, bool $newStatus): User
    {
        match ($newStatus) {
            true => $user->enable(),
            false => $user->disable()
        };

        return $user;
    }

    private function validateActualUserStatus(User $user, bool $newStatus): void
    {
        if ($user->status() !== $newStatus) {
            return;
        }

        if ($newStatus) {
            $errorMsg = "User '" . $user->email() . "' is already enabled";
            $this->logger->error($errorMsg);
            throw new UpdateUserException($errorMsg, 1052, null, [1 => $user->email()]);
        }

        $errorMsg = "User '" . $user->email() . "' is already disabled";
        $this->logger->error($errorMsg);
        throw new UpdateUserException($errorMsg, 1051, null, [1 => $user->email()]);
    }

    private function validateUserUpdate(bool $userUpdateStatus, string $userEmail): void
    {
        if (!$userUpdateStatus) {
            $errorMsg = "Error updating the status of '$userEmail' user";
            $this->logger->error($errorMsg);
            throw new UpdateUserException($errorMsg, 1012, null, [1 => $userEmail]);
        }

        $this->logger->info("User '$userEmail' status updated successfully");
    }
}
