<?php

/**
 * CreateUser.
 *
 * PHP Version 8
 *
 * @author  xsga <parker@xsga.es>
 * @license MIT
 * @version 1.0.0
 */

/**
 * Namespace.
 */
namespace Xsga\FilmAffinityApi\Business\Users;

/**
 * Import dependencies.
 */
use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Dto\UserDto;
use Xsga\FilmAffinityApi\Entities\ApiUsers;
use Xsga\FilmAffinityApi\Helpers\Password\PasswordInterface;
use Xsga\FilmAffinityApi\Repositories\UsersRepositoryInterface;

/**
 * CreateUser class.
 */
final class CreateUser
{
    /**
     * Constructor.
     */
    public function __construct(
        private LoggerInterface $logger,
        private UsersRepositoryInterface $repository,
        private PasswordInterface $password
    ) {
    }

    /**
     * Create user.
     */
    public function create(UserDto $userDto): int
    {
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
