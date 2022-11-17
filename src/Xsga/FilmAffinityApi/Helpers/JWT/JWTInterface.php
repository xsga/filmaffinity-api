<?php

/**
 * JWTInterface.
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
namespace Xsga\FilmAffinityApi\Helpers\JWT;

/**
 * Import dependencies.
 */
use Exception;

/**
 * JWTInterface.
 */
interface JWTInterface
{
    /**
     * Get JWT token.
     *
     * @param string $userEmail User e-mail.
     *
     * @return string
     *
     * @access public
     */
    public function get(string $userEmail): string;

    /**
     * Validate JWT token.
     *
     * @param string $token JWT token.
     *
     * @return array
     *
     * @throws Exception
     *
     * @access public
     */
    public function validate(string $token): array;

    /**
     * Decode JWT token.
     *
     * @param string $token JWT token.
     *
     * @return array
     *
     * @access public
     */
    public function decode(string $token): array;
}
