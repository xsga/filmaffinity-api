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
     * Logger.
     *
     * @var LoggerInterface
     *
     * @access private
     */
    private $logger;

    /**
     * Users repository.
     *
     * @var UsersRepositoryInterface
     *
     * @access private
     */
    private $repository;

    /**
     * Password service.
     *
     * @var PasswordInterface
     *
     * @access private
     */
    private $password;

    /**
     * Constructor.
     *
     * @param LoggerInterface          $logger       LoggerInterface instance.
     * @param UsersRepositoryInterface $repository   UserRespositoryInterface interface.
     *
     * @access public
     */
    public function __construct(
        LoggerInterface $logger,
        UsersRepositoryInterface $repository,
        PasswordInterface $password
    ) {
        $this->logger     = $logger;
        $this->repository = $repository;
        $this->password   = $password;
    }

    /**
     * Create user.
     *
     * @param UserDto $userDto User DTO.
     *
     * @return integer
     *
     * @access public
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
