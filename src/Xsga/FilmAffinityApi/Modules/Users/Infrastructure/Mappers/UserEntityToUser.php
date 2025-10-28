<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Mappers;

use Xsga\FilmAffinityApi\Modules\Shared\Persistence\Infrastructure\Doctrine\UsersEntity;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Model\User;

final class UserEntityToUser
{
    public function convert(UsersEntity $userEntity): User
    {
        return new User(
            $this->getUserId($userEntity->getUserId()),
            $userEntity->getEmail(),
            $userEntity->getPassword(),
            false,
            $userEntity->getStatus(),
            $userEntity->getCreateDate(),
            $userEntity->getUpdateDate()
        );
    }

    private function getUserId(?int $userId): int
    {
        return $userId === null ? 0 : $userId;
    }

    /**
     * @param UsersEntity[] $userEntities
     *
     * @return User[]
     */
    public function convertArray(array $userEntities): array
    {
        return array_map(
            fn(UsersEntity $entity): User => $this->convert($entity),
            $userEntities
        );
    }
}
