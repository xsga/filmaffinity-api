<?php

/**
 * Create user command validator.
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
namespace Xsga\FilmAffinityApi\Commands\Validators;

/**
 * Import dependencies.
 */
use Psr\Log\LoggerInterface;
use RuntimeException;
use Xsga\FilmAffinityApi\Business\Users\GetUser;
use Xsga\FilmAffinityApi\Helpers\Validators\EmailValidator;
use Xsga\FilmAffinityApi\Helpers\Validators\PasswordValidator;

/**
 * CreateUserValidator class.
 */
class CreateUserValidator
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
     * Email validator.
     *
     * @var EmailValidator
     *
     * @access private
     */
    private $email;

    /**
     * Password validator.
     *
     * @var PasswordValidator
     *
     * @access private
     */
    private $password;

    /**
     * Get user service.
     *
     * @var GetUser
     *
     * @access private
     */
    private $getUser;

    /**
     * Constructor.
     *
     * @param LoggerInterface   $logger            LoggerInterface instance.
     * @param EmailValidator    $emailValidator    EmailValidator instance.
     * @param PasswordValidator $passwordValidator PasswordValidator instance.
     * @param GetUser           $getUser           GetUser instance.
     *
     * @access public
     */
    public function __construct(
        LoggerInterface $logger,
        EmailValidator $emailValidator,
        PasswordValidator $passwordValidator,
        GetUser $getUser
    ) {
        $this->logger   = $logger;
        $this->email    = $emailValidator;
        $this->password = $passwordValidator;
        $this->getUser  = $getUser;
    }

    /**
     * Validate e-mail.
     *
     * @param string $email E-mail.
     *
     * @return string|null
     *
     * @throws RuntimeException E-mail not valid.
     *
     * @access public
     */
    public function validateEmail(string|null $email): string
    {
        $email = $email === null ? '' : $email;

        if ($this->email->validate($email)) {
            return $email;
        }//end if

        throw new RuntimeException('E-mail not valid');
    }

    /**
     * Validate password.
     *
     * @param string $password Password.
     *
     * @return string|null
     *
     * @throws RuntimeException Password not valid.
     *
     * @access public
     */
    public function validatePassword(string|null $password): string
    {
        $password = $password === null ? '' : $password;

        if ($this->password->validate($password)) {
            return $password;
        }//end if

        $errorMsg  = 'Password must have a minimum eight characters, at least one uppercase letter, ';
        $errorMsg .= 'one lowercase letter, one number and one special character';

        throw new RuntimeException($errorMsg);
    }

    /**
     * Validates if user exists.
     *
     * @param string $email User e-mail.
     *
     * @return boolean
     *
     * @access public
     */
    public function validateUserExists(string $email): bool
    {
        $userDto = $this->getUser->byEmail($email);

        if ($userDto->userId === 0) {
            return false;
        }//end if

        return true;
    }
}
