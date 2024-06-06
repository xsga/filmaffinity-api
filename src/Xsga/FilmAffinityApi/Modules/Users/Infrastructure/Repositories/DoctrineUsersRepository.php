<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Throwable;
use Xsga\FilmAffinityApi\Modules\Shared\Persistence\Infrastructure\Doctrine\UsersEntity;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Exceptions\UserNotFoundException;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Model\User;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Repositories\UsersRepository;
use Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Mappers\UserEntityToUser;
use Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Mappers\UserToNewUserEntity;

final class DoctrineUsersRepository implements UsersRepository
{
    public function __construct(
        private LoggerInterface $logger,
        private EntityManagerInterface $em,
        private UserEntityToUser $userEntityToModelMapper,
        private UserToNewUserEntity $userToNewEntityMapper
    ) {
    }

    /**
     * @return User[]
     */
    public function getAllUsers(): array
    {
        $usersRepository = $this->em->getRepository(UsersEntity::class);
        $searchResult    = $usersRepository->findAll();

        return match ($searchResult) {
            null => [],
            default => $this->userEntityToModelMapper->convertArray($searchResult)
        };
    }

    public function getUserByEmail(string $userEmail): ?User
    {
        $usersRepository = $this->em->getRepository(UsersEntity::class);
        $userEntity      = $usersRepository->findOneBy(['email' => $userEmail]);

        return match ($userEntity) {
            null => null,
            default => $this->userEntityToModelMapper->convert($userEntity)
        };
    }

    public function getUserById(int $userId): ?User
    {
        $userEntity = $this->getUserEntityById($userId);

        return match ($userEntity) {
            null => null,
            default => $this->userEntityToModelMapper->convert($userEntity)
        };
    }

    public function createUser(User $user): int
    {
        try {
            $userEntity = $this->userToNewEntityMapper->convert($user);

            $this->em->persist($userEntity);
            $this->em->flush();

            $userId = $userEntity->getUserId();

            return match (is_null($userId)) {
                true => -1,
                false => $userId
            };
        } catch (Throwable $exception) {
            $this->logger->error($exception->__toString());
            return match ($exception->getCode()) {
                1062 => 0,
                default => -1
            };
        }
    }

    public function updatePassword(User $user): bool
    {
        try {
            $query = $this->em->createQueryBuilder();

            $query->update(UsersEntity::class, 'u');
            $query->set('u.password', ':userPassword');
            $query->set('u.updateDate', ':userUpdateDate');
            $query->setParameter(':userPassword', $user->password());
            $query->setParameter(':userUpdateDate', $user->updateDate()->format('Y-m-d H:i:s'));
            $query->where('u.userId = ' . $user->userId());
            $query->getQuery()->execute();

            return true;
        } catch (Throwable $exception) {
            $this->logger->error($exception->__toString());
            return false;
        }
    }

    public function updateUserStatus(User $user): bool
    {
        try {
            $query = $this->em->createQueryBuilder();

            $query->update(UsersEntity::class, 'u');
            $query->set('u.status', ':userStatus');
            $query->set('u.updateDate', ':userUpdateDate');
            $query->setParameter(':userStatus', $user->status());
            $query->setParameter(':userUpdateDate', $user->updateDate()->format('Y-m-d H:i:s'));
            $query->where('u.userId = ' . $user->userId());
            $query->getQuery()->execute();

            return true;
        } catch (Throwable $exception) {
            $this->logger->error($exception->__toString());
            return false;
        }
    }

    public function deleteUser(int $userId): bool
    {
        try {
            $userEntity = $this->getUserEntityByIdExit($userId);

            $this->em->remove($userEntity);
            $this->em->flush();

            return true;
        } catch (Throwable $exception) {
            $this->logger->error($exception->__toString());
            return false;
        }
    }

    public function deleteUserByEmail(string $userEmail): bool
    {
        try {
            $userEntity = $this->getUserEntityByEmailExit($userEmail);

            $this->em->remove($userEntity);
            $this->em->flush();

            return true;
        } catch (Throwable $exception) {
            $this->logger->error($exception->__toString());
            return false;
        }
    }

    private function getUserEntityById(int $userId): ?UsersEntity
    {
        $usersRepository = $this->em->getRepository(UsersEntity::class);

        return $usersRepository->find($userId);
    }

    private function getUserEntityByIdExit(int $userId): UsersEntity
    {
        $userEntity = $this->getUserEntityById($userId);

        if ($userEntity === null) {
            $message = "User '$userId' not found";
            $this->logger->error($message);
            throw new UserNotFoundException($message);
        }

        return $userEntity;
    }

    private function getUserEntityByEmail(string $userEmail): ?UsersEntity
    {
        $usersRepository = $this->em->getRepository(UsersEntity::class);

        return $usersRepository->findOneBy(['email' => $userEmail]);
    }

    private function getUserEntityByEmailExit(string $userEmail): UsersEntity
    {
        $userEntity = $this->getUserEntityByEmail($userEmail);

        if ($userEntity === null) {
            $message = "User '$userEmail' not found";
            $this->logger->error($message);
            throw new UserNotFoundException($message);
        }

        return $userEntity;
    }
}
