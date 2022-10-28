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
use Xsga\FilmAffinityApi\Helpers\JWT\JWT;

/**
 * GetToken class.
 */
final class GetToken
{
    /**
     * JWI interface instance.
     *
     * @var JWT
     *
     * @access private
     */
    private $jwt;

    /**
     * User login service.
     *
     * @var UserLogin
     *
     * @access private
     */
    private $userLogin;

    /**
     * Constructor.
     *
     * @param LoggerInterface $logger LoggerInterface instance.
     * @param JWT             $jwt    JWT instance.
     * @param UserLogin       $login  UserLogin instance.
     *
     * @access public
     */
    public function __construct(
        LoggerInterface $logger,
        JWT $jwt,
        UserLogin $login
    ) {
        $this->jwt       = $jwt;
        $this->userLogin = $login;
    }

    /**
     * Get token.
     *
     * @param string $user     Username.
     * @param string $password User password.
     *
     * @return string
     *
     * @access public
     */
    public function get(string $user, string $password): string
    {
        $userDto = $this->userLogin->login($user, $password);

        return $this->jwt->get($userDto->email);
    }
}
