<?php

/**
 * PasswordInterface.
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
namespace Xsga\FilmAffinityApi\Helpers\Password;

/**
 * PasswordInterface.
 */
interface PasswordInterface
{
    /**
     * Get password hash.
     *
     * @param string $password Password to encrypt.
     *
     * @return string
     *
     * @access public
     */
    public function getHash(string $password): string;

    /**
     * Verify password.
     *
     * @param string $password Password.
     * @param string $hash     Password hash.
     *
     * @return boolean
     *
     * @access public
     */
    public function verify(string $password, string $hash): bool;

    /**
     * Validates if password needs rehash.
     *
     * @param string $hash Hash to validates.
     *
     * @return boolean
     *
     * @access public
     */
    public function needsRehash(string $hash): bool;
}
