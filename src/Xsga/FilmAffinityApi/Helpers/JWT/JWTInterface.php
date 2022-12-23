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
     */
    public function get(string $userEmail): string;

    /**
     * Validate JWT token.
     *
     * @throws Exception
     */
    public function validate(string $token): array;

    /**
     * Decode JWT token.
     */
    public function decode(string $token): array;
}
