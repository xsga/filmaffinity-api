<?php

/**
 * Password.
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
 * Password class.
 */
final class Password implements PasswordInterface
{
    /**
     * Hash method.
     *
     * @var int
     */
    private const HASH = PASSWORD_DEFAULT;

    /**
     * Password cost.
     *
     * @var int
     */
    private const COST = 10;

    /**
     * Get password hash.
     */
    public function getHash(string $password): string
    {
        return password_hash($password, self::HASH, ['cost' => self::COST]);
    }

    /**
     * Verify password.
     */
    public function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Validates if password needs rehash.
     */
    public function needsRehash(string $hash): bool
    {
        return password_needs_rehash($hash, self::HASH, ['cost' => self::COST]);
    }
}
