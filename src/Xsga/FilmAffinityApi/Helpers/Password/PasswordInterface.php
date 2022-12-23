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
     */
    public function getHash(string $password): string;

    /**
     * Verify password.
     */
    public function verify(string $password, string $hash): bool;

    /**
     * Validates if password needs rehash.
     */
    public function needsRehash(string $hash): bool;
}
