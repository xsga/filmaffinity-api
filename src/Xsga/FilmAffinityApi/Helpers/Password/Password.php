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
     *
     * @access private
     */
    private const HASH = PASSWORD_DEFAULT;

    /**
     * Password cost.
     *
     * @var int
     *
     * @access private
     */
    private const COST = 10;

    /**
     * Get password hash.
     *
     * @param string $password Password to encrypt.
     *
     * @return string
     *
     * @access public
     */
    public function getHash(string $password): string
    {
        return password_hash($password, self::HASH, ['cost' => self::COST]);
    }

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
    public function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Validates if password needs rehash.
     *
     * @param string $hash Hash to validates.
     *
     * @return boolean
     *
     * @access public
     */
    public function needsRehash(string $hash): bool
    {
        return password_needs_rehash($hash, self::HASH, ['cost' => self::COST]);
    }
}
