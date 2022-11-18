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
     * @param LoggerInterface          $logger     LoggerInterface instance.
     * @param UsersRepositoryInterface $repository UserRespositoryInterface interface.
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
     * Change user status.
     *
     * @param string $userEmail User e-mail.
     * @param int    $status    Status.
     *
     * @return boolean
     *
     * @access public
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
