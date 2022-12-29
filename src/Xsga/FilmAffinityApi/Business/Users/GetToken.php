<?php

/**
 * GetToken.
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
use Xsga\FilmAffinityApi\Helpers\JWT\JWTInterface;

/**
 * GetToken class.
 */
final class GetToken
{
    /**
     * Constructor.
     */
    public function __construct(
        private LoggerInterface $logger,
        private JWTInterface $jwt,
        private UserLogin $userLogin
    ) {
    }

    /**
     * Get token.
     */
    public function get(string $user, string $password): string
    {
        $userDto = $this->userLogin->login($user, $password);

        return $this->jwt->get($userDto->email);
    }
}
