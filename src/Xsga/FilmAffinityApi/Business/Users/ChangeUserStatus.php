<?php

/**
 * ChangeUserStatus.
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
use Xsga\FilmAffinityApi\Repositories\UsersRepositoryInterface;

/**
 * ChangeUserStatus class.
 */
final class ChangeUserStatus
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
     * Change user status.
     */
    public function change(string $userEmail, int $status): bool
    {
        if ($status !== 0 && $status !== 1) {
            $this->logger->error("Status \"$status\" not valid");
            return false;
        }//end if

        $userEntity = $this->repository->getUser($userEmail);

        if (empty($userEntity)) {
            $this->logger->error("User \"$userEmail\" not found");
            return false;
        }//end if

        // Set status.
        $userEntity->setEnabled($status);

        if (!$this->repository->updateUser($userEntity)) {
            $this->logger->error('Error changing user status');
            return false;
        }//end if

        return true;
    }
}
