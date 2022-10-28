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
     *
     * @param string $userEmail User email.
     *
     * @return ApiUsers|null
     *
     * @access public
     */
    public function getUser(string $userEmail): ApiUsers|null;

    /**
     * Add user.
     *
     * @param ApiUsers $user User entity.
     *
     * @return integer
     *
     * @access public
     */
    public function addUser(ApiUsers $user): int;

    /**
     * Update user.
     *
     * @param ApiUsers $user User entity.
     *
     * @return boolean
     *
     * @access public
     */
    public function updateUser(ApiUsers $user): bool;
}
