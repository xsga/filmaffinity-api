<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Mappers;

use Xsga\FilmAffinityApi\Modules\Shared\Persistence\Infrastructure\Doctrine\UsersEntity;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Model\User;

final class UserToNewUserEntity
{
    public function convert(User $user): UsersEntity
    {
        $userEntity = new UsersEntity();

        $userEntity->setEmail($user->email());
        $userEntity->setPassword($user->password());
        $userEntity->setName($user->name());
        $userEntity->setCreateDate($user->createDate());
        $userEntity->setUpdateDate($user->updateDate());
        $userEntity->setStatus($user->status());

        return $userEntity;
    }
}
