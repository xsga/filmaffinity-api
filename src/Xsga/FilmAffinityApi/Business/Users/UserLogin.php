<?php

/**
 * UserLogin.
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
use Xsga\FilmAffinityApi\Dto\UserDto as UserDto;
use Xsga\FilmAffinityApi\Exceptions\UserDisabledException;
use Xsga\FilmAffinityApi\Exceptions\UserLoginException;
use Xsga\FilmAffinityApi\Exceptions\UserNotFoundException;
use Xsga\FilmAffinityApi\Helpers\Password\PasswordInterface;

/**
 * UserLogin class.
 */
final class UserLogin
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
     * GetUser service.
     *
     * @var GetUser
     *
     * @access private
     */
    private $getUser;

    /**
     * PasswordInterface.
     *
     * @var PasswordInterface
     *
     * @access private
     */
    private $passUtils;

    /**
     * Constructor.
     *
     * @param LoggerInterface   $logger    LoggerInterface instance.
     * @param GetUser           $getUser   GetUser instance.
     * @param PasswordInterface $passUtils PaswwordInterface instance.
     *
     * @access public
     */
    public function __construct(
        LoggerInterface $logger,
        GetUser $getUser,
        PasswordInterface $passUtils
    ) {
        $this->logger    = $logger;
        $this->getUser   = $getUser;
        $this->passUtils = $passUtils;
    }

    /**
     * Login user.
     *
     * @param string $user     Username.
     * @param string $password User password.
     *
     * @return UserDto
     *
     * @throws UserNotFoundException User not found.
     * @throws UserDisabledException User disabled.
     * @throws UserLoginException    Wrong user password.
     *
     * @access public
     */
    public function login(string $user, string $password): UserDto
    {
        $userData = $this->getUser->byEmail($user);

        if ($userData->id === 0) {
            $errorMsg = "User \"$user\" not found";
            $this->logger->error($errorMsg);
            throw new UserNotFoundException($errorMsg, 1016);
        }//end if

        if ($userData->enabled === 0) {
            $errorMsg = "User \"$user\" disabled";
            $this->logger->error($errorMsg);
            throw new UserDisabledException($errorMsg, 1017);
        }//end if

        if (!$this->passUtils->verify($password, $userData->password)) {
            $errorMsg = 'Wrong user password';
            $this->logger->error($errorMsg);
            throw new UserLoginException($errorMsg, 1018);
        }//end if

        $this->logger->debug("User \"$user\" login successfully");

        return $userData;
    }
}
