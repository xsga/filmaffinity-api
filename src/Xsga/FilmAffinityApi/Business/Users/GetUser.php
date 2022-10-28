<?php

/**
 * GetUser.
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
use Xsga\FilmAffinityApi\Repositories\UsersRepositoryInterface;

/**
 * GetUser class.
 */
final class GetUser
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
     * Constructor.
     *
     * @param LoggerInterface          $logger       LoggerInterface instance.
     * @param UsersRepositoryInterface $repository   UserRespositoryInterface interface.
     *
     * @access public
     */
    public function __construct(
        LoggerInterface $logger,
        UsersRepositoryInterface $repository
    ) {
        $this->logger     = $logger;
        $this->repository = $repository;
    }

    /**
     * Get user by e-mail.
     *
     * @param string $userEmail User email.
     *
     * @return UserDto
     *
     * @access public
     */
    public function byEmail(string $userEmail): UserDto
    {
        $userEntity = $this->repository->getUser($userEmail);

        $userDto = new UserDto();

        if (empty($userEntity)) {
            $this->logger->warning("User \"$userEmail\" not found");
            return $userDto;
        }//end if

        // Maps entity to DTO.
        $userDto->id         = $userEntity->getId();
        $userDto->email      = $userEntity->getEmail();
        $userDto->password   = $userEntity->getPassword();
        $userDto->createDate = $userEntity->getCreateDate();
        $userDto->enabled    = $userEntity->getEnabled();

        return $userDto;
    }
}
