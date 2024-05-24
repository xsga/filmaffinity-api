<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Business\Users;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Dto\UserDto;
use Xsga\FilmAffinityApi\Repositories\UsersRepositoryInterface;

final class GetUser
{
    public function __construct(
        private LoggerInterface $logger,
        private UsersRepositoryInterface $repository
    ) {
    }

    public function byEmail(string $userEmail): UserDto
    {
        $userEntity = $this->repository->getUser($userEmail);

        $userDto = new UserDto();

        if (empty($userEntity)) {
            $this->logger->warning("User '$userEmail' not found");
            return $userDto;
        }

        $userDto->userId     = $userEntity->getId();
        $userDto->email      = $userEntity->getEmail();
        $userDto->password   = $userEntity->getPassword();
        $userDto->createDate = $userEntity->getCreateDate();
        $userDto->enabled    = $userEntity->getEnabled();

        return $userDto;
    }
}
