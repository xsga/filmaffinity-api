<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Domain\Services;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Exceptions\UserNotFoundException;
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

        return $this->validateUserSearch($user, (string)$userId);
    }

    public function byEmail(string $userEmail): User
    {
        $user = $this->usersRepository->getUserByEmail($userEmail);

        return $this->validateUserSearch($user, $userEmail);
    }

    private function validateUserSearch(?User $user, string $criteriaValue): User
    {
        if (is_null($user)) {
            $errorMsg = "User '$criteriaValue' not found";
            $this->logger->error($errorMsg);
            throw new UserNotFoundException($errorMsg, 1005, null, [1 => $criteriaValue]);
        }

        return $user;
    }
}
