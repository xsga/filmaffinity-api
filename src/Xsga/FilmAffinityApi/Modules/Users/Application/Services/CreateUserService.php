<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\App\Application\Services\Users;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\App\Application\Dto\UserDto;

final class CreateUserService
{
    public function __construct(
        private LoggerInterface $logger,
        private UsersRepositoryInterface $repository,
        private PasswordInterface $password
    ) {
    }

    public function create(UserDto $userDto): int
    {
        // TODO: map to User (model) and repository save.
        $userEntity = new ApiUsers();

        $userEntity->setEmail($userDto->email);
        $userEntity->setPassword($this->password->getHash($userDto->password));
        $userEntity->setRole($userDto->role);
        $userEntity->setEnabled($userDto->enabled);
        $userEntity->setCreateDate($userDto->createDate);

        $userId = $this->repository->addUser($userEntity);

        return $userId;
    }
}
