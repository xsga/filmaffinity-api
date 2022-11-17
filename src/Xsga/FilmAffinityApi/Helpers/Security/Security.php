<?php

/**
 * Security.
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
namespace Xsga\FilmAffinityApi\Helpers\Security;

/**
 * Import dependencies.
 */
use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Business\Users\UserLogin;
use Xsga\FilmAffinityApi\Exceptions\AuthHeaderNotValidException;
use Xsga\FilmAffinityApi\Helpers\JWT\JWTInterface;

/**
 * Class Security.
 */
final class Security implements SecurityInterface
{
    /**
     * Logger.
     *
     * @var LoggerInterface
     *
     * @access private
     */
    private $logger;

    /**
     * JWT service.
     *
     * @var JWTInterface
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
     *
     * @access public
     */
    public function __construct(LoggerInterface $logger, JWTInterface $jwt, UserLogin $userLogin)
    {
        $this->logger    = $logger;
        $this->jwt       = $jwt;
        $this->userLogin = $userLogin;
    }

    /**
     * Basic security.
     *
     * @param string $authorization Authorization header.
     *
     * @return string
     *
     * @access public
     */
    public function basic(string $authorization): string
    {
        $this->logger->debug('BASIC security');

        $authToken       = $this->validateAuthorization($authorization, 'basic');
        $userAndPassword = $this->getUserAndPassword($authToken);

        $userDto = $this->userLogin->login($userAndPassword['user'], $userAndPassword['password']);

        return $userDto->email;
    }

    /**
     * Token security.
     *
     * @param string $authorization Authorization header.
     *
     * @return string
     *
     * @access public
     */
    public function token(string $authorization): string
    {
        $this->logger->debug('TOKEN security');

        $authToken = $this->validateAuthorization($authorization, 'bearer');
        $jwtData   = $this->jwt->validate($authToken);

        return $jwtData['user'];
    }

    /**
     * Validates authorization header.
     *
     * @param string $authHeader Authorization header.
     * @param string $type       Authorization type.
     *
     * @return string
     *
     * @throws AuthHeaderNotValidException Authorization header not valid.
     * @throws AuthHeaderNotValidException Authorization header not match with API security.
     *
     * @access private
     */
    private function validateAuthorization(string $authHeader, string $type): string
    {
        $authArray = explode(' ', $authHeader);

        if (count($authArray) !== 2) {
            $errorMsg = 'Authorization header not valid';
            $this->logger->error($errorMsg);
            throw new AuthHeaderNotValidException($errorMsg, 1013);
        }//end if

        if (strtolower($authArray[0]) !== $type) {
            $errorMsg = 'Authorization header not match with API security type';
            $this->logger->error($errorMsg);
            throw new AuthHeaderNotValidException($errorMsg, 1014);
        }//end if

        return $authArray[1];
    }

    /**
     * Gets user and password from basic authorization header.
     *
     * @param string $authorization Request authorization token.
     *
     * @return array
     *
     * @throws AuthHeaderNotValidException User and password not valid.
     *
     * @access private
     */
    private function getUserAndPassword(string $authorization): array
    {
        $userAndPassword = explode(':', base64_decode($authorization));

        if (count($userAndPassword) !== 2) {
            $error = 'User and password token not valid';
            $this->logger->error($error);
            throw new AuthHeaderNotValidException($error, 1013);
        }//end if

        $out             = array();
        $out['user']     = $userAndPassword[0];
        $out['password'] = $userAndPassword[1];

        return $out;
    }
}
