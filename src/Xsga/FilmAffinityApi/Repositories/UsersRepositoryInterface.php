<?php

/**
 * UsersRepositoryInterface.
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
use Xsga\FilmAffinityApi\Entities\ApiUsers;

/**
 * UsersRepositoryInterface.
 */
interface UsersRepositoryInterface
{
    /**
     * Get user.
     */
    public function getUser(string $userEmail): ApiUsers|null;

    /**
     * Add user.
     */
    public function addUser(ApiUsers $user): int;

    /**
     * Update user.
     */
    public function updateUser(ApiUsers $user): bool;
}
