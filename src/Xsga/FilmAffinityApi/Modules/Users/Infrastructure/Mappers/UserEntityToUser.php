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
        return match (is_null($userId)) {
            true => 0,
            false => $userId
        };
    }

    /**
     * @param UsersEntity[] $userEntities
     *
     * @return User[]
     */
    public function convertArray(array $userEntities): array
    {
        $out = [];

        foreach ($userEntities as $userEntity) {
            $out[] = $this->convert($userEntity);
        }

        return $out;
    }
}
