<?php

/**
 * UsersRepository.
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
namespace Xsga\FilmAffinityApi\Repositories;

/**
 * Import dependencies.
 */
use Throwable;
use Xsga\FilmAffinityApi\Entities\ApiUsers;

/**
 * UsersRepository.
 */
final class UsersRepository extends AbstractRepository implements UsersRepositoryInterface
{
    /**
     * Get user.
     */
    public function getUser(string $userEmail): ApiUsers|null
    {
        $criteria = array('email' => $userEmail);

        $userEntity = $this->repository->findOneBy($criteria);

        return $userEntity;
    }

    /**
     * Add user.
     */
    public function addUser(ApiUsers $user): int
    {
        $this->em->persist($user);
        $this->em->flush();

        return $user->getId();
    }

    /**
     * Update user.
     */
    public function updateUser(ApiUsers $user): bool
    {
        try {
            $this->em->persist($user);
            $this->em->flush();
        } catch (Throwable $e) {
            $this->logger->error($e->__toString());
            return false;
        }//end try

        return true;
    }
}
