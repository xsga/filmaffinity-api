<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Application\Services;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Users\Application\Dto\UpdateUserDto;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Exceptions\UpdateUserException;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Repositories\UsersRepository;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Services\GetUser;

final class UpdateUserService
{
    public function __construct(
        private LoggerInterface $logger,
        private UsersRepository $usersRepository,
        private GetUser $getUser
    ) {
    }

    public function update(UpdateUserDto $userData): bool
    {
        $user = $this->getUser->byId($userData->userId);
        $user->updateName($userData->name);

        $userUpdateStatus = $this->usersRepository->updateUser($user);

        $this->validateUserUpdate($userUpdateStatus, $user->email());

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
