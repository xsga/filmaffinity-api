<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Application\Services;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Users\Application\Dto\UpdatePasswordDto;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Exceptions\UpdateUserException;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Repositories\UsersRepository;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Services\GetUser;

final class UpdatePasswordService
{
    public function __construct(
        private LoggerInterface $logger,
        private UsersRepository $usersRepository,
        private GetUser $getUser
    ) {
    }

    public function update(UpdatePasswordDto $userData): bool
    {
        $user = $this->getUser->byId($userData->userId);
        $user->updatePassword($userData->newPassword);

        $passwordUpdateStatus = $this->usersRepository->updatePassword($user);

        $this->validateUserUpdate($passwordUpdateStatus, $user->email());

        $this->logger->info("User '" . $user->email() . "' updated successfully");

        return true;
    }

    private function validateUserUpdate(bool $userUpdateStatus, string $userEmail): void
    {
        if (!$userUpdateStatus) {
            $errorMsg = "Error updating user '$userEmail'";
            $this->logger->error($errorMsg);
            throw new UpdateUserException($errorMsg, 1012, null, [1 => $userEmail]);
        }
    }
}
