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
     * Constructor.
     */
    public function __construct(
        private LoggerInterface $logger,
        private UsersRepositoryInterface $repository
    ) {
    }

    /**
     * Get user by e-mail.
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
        $userDto->userId     = $userEntity->getId();
        $userDto->email      = $userEntity->getEmail();
        $userDto->password   = $userEntity->getPassword();
        $userDto->createDate = $userEntity->getCreateDate();
        $userDto->enabled    = $userEntity->getEnabled();

        return $userDto;
    }
}
