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
     * Constructor.
     */
    public function __construct(
        private LoggerInterface $logger,
        private JWTInterface $jwt,
        private UserLogin $userLogin
    ) {
    }

    /**
     * Basic security.
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
     * @throws AuthHeaderNotValidException Authorization header not valid.
     * @throws AuthHeaderNotValidException Authorization header not match with API security.
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
     * @throws AuthHeaderNotValidException User and password not valid.
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
