<?php

/**
 * Enable user command validator.
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

/**
 * ChangeUserStatusValidator class.
 */
class ChangeUserStatusValidator
{
    /**
     * Constructor.
     */
    public function __construct(
        private LoggerInterface $logger,
        private EmailValidator $emailValidator,
        private GetUser $getUser
    ) {
    }

    /**
     * Validate e-mail.
     *
     * @throws RuntimeException E-mail not valid.
     */
    public function validateEmail(string $email): string
    {
        if ($this->emailValidator->validate($email)) {
            return $email;
        }//end if

        throw new RuntimeException('E-mail not valid');
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
