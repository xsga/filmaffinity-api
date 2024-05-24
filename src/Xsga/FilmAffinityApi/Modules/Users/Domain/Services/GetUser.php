<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Domain\Services;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Exceptions\Users\UserNotFoundException;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Model\User;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Repositories\UsersRepository;

final class GetUser
{
    public function __construct(
        private LoggerInterface $logger,
        private UsersRepository $usersRepository
    ) {
    }

    public function byId(int $userId): User
    {
        $user = $this->usersRepository->getUserById($userId);

        $this->validateUserSearch($user, (string)$userId);

        return $user;
    }

    public function byEmail(string $userEmail): User
    {
        $user = $this->usersRepository->getUserByEmail($userEmail);

        $this->validateUserSearch($user, $userEmail);

        return $user;
    }

    private function validateUserSearch(?User $user, string $criteriaValue): void
    {
        if ($user === null) {
            $errorMsg = "User '$criteriaValue' not found";
            $this->logger->error($errorMsg);
            throw new UserNotFoundException($errorMsg, 1005, null, [1 => $criteriaValue]);
        }
    }
}
