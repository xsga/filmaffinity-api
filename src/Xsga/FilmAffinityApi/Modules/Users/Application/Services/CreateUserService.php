<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Application\Services;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Users\Application\Dto\CreateUserDto;
use Xsga\FilmAffinityApi\Modules\Users\Application\Mappers\CreateUserDtoToUser;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Exceptions\CreateUserException;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Exceptions\UserExistsException;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Repositories\UsersRepository;

final class CreateUserService
{
    public function __construct(
        private LoggerInterface $logger,
        private CreateUserDtoToUser $mapper,
        private UsersRepository $usersRepository
    ) {
    }

    public function create(CreateUserDto $userData): int
    {
        $user   = $this->mapper->convert($userData);
        $userId = $this->usersRepository->createUser($user);

        $this->validateUserCreation($userId, $user->email());

        return $userId;
    }

    private function validateUserCreation(int $userId, string $userEmail): void
    {
        if ($userId === 0) {
            $errorMsg = "User '$userEmail' already exists";
            $this->logger->error($errorMsg);
            throw new UserExistsException($errorMsg, 1022, null, [1 => $userEmail]);
        }

        if ($userId === -1) {
            $errorMsg = "Error creating user '$userEmail'";
            $this->logger->error($errorMsg);
            throw new CreateUserException($errorMsg, 1013, null, [1 => $userEmail]);
        }

        $this->logger->info("User '$userEmail' created successfully (ID: $userId)");
    }
}
