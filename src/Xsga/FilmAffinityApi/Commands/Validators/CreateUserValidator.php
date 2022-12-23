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
     * Constructor.
     */
    public function __construct(
        private LoggerInterface $logger,
        private EmailValidator $email,
        private PasswordValidator $password,
        private GetUser $getUser
    ) {
    }

    /**
     * Validate e-mail.
     *
     * @throws RuntimeException E-mail not valid.
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
     * @throws RuntimeException Password not valid.
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
