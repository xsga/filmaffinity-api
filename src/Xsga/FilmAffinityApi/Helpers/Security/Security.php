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
use Xsga\FilmAffinityApi\Exceptions\AuthHeaderNotValidException;
use Xsga\FilmAffinityApi\Helpers\JWT\JWT;

/**
 * Class Security.
 */
final class Security
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
     * @var JWT
     *
     * @access private
     */
    private $jwt;

    /**
     * Constructor.
     *
     * @param LoggerInterface $logger LoggerInterface instance.
     *
     * @access public
     */
    public function __construct(LoggerInterface $logger, JWT $jwt)
    {
        $this->logger = $logger;
        $this->jwt    = $jwt;
    }

    /**
     * Basic security.
     *
     * @param string $authorization Authorization header.
     *
     * @return void
     *
     * @access public
     */
    public function basic(string $authorization): void
    {
        $this->logger->debug('BASIC security');

        $authToken = $this->validateAuthorization($authorization, 'basic');

        $userAndPassword = $this->getUserAndPassword($authToken);

        // TODO: login user.
        $this->logger->debug('User: ' . $userAndPassword['user']);
        $this->logger->debug('Password: ' . $userAndPassword['password']);
    }

    /**
     * Token security.
     *
     * @param string $authorization Authorization header.
     *
     * @return void
     *
     * @access public
     */
    public function token(string $authorization): void
    {
        $this->logger->debug('TOKEN security');

        $authToken = $this->validateAuthorization($authorization, 'bearer');

        $this->jwt->validate($authToken);
    }

    /**
     * Validates authorization header.
     *
     * @param string $authorization Authorization header.
     * @param string $type          Authorization type.
     *
     * @return string
     *
     * @throws AuthHeaderNotValidException Authorization header not valid.
     * @throws AuthHeaderNotValidException Authorization header not match with API security.
     *
     * @access private
     */
    private function validateAuthorization(string $authorization, string $type): string
    {
        $authArray = explode(' ', $authorization);

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
