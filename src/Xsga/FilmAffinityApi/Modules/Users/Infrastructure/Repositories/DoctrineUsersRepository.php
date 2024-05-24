<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Repositories;

use Throwable;
use Xsga\FilmAffinityApi\App\Domain\Model\User;
use Xsga\FilmAffinityApi\App\Domain\Repositories\UsersRepository;

final class DoctrineUsersRepository implements UsersRepository
{
    public function getUserByEmail(string $userEmail): ?User
    {
        $criteria = array('email' => $userEmail);

        $userEntity = $this->repository->findOneBy($criteria);

        return $userEntity;
    }

    public function addUser(User $user): int
    {
        $this->em->persist($user);
        $this->em->flush();

        return $user->getId();
    }

    public function updateUser(User $user): bool
    {
        try {
            $this->em->persist($user);
            $this->em->flush();
        } catch (Throwable $e) {
            $this->logger->error($e->__toString());
            return false;
        }

        return true;
    }
}
