<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Commands\Validators;

use Psr\Log\LoggerInterface;
use RuntimeException;
use Xsga\FilmAffinityApi\Business\Users\GetUser;
use Xsga\FilmAffinityApi\Helpers\Validators\EmailValidator;
use Xsga\FilmAffinityApi\Helpers\Validators\PasswordValidator;

class CreateUserValidator
{
    public function __construct(
        private LoggerInterface $logger,
        private EmailValidator $email,
        private PasswordValidator $password,
        private GetUser $getUser
    ) {
    }

    public function validateEmail(string|null $email): string
    {
        $email = $email === null ? '' : $email;

        if ($this->email->validate($email)) {
            return $email;
        }

        throw new RuntimeException('E-mail not valid');
    }

    public function validatePassword(string|null $password): string
    {
        $password = $password === null ? '' : $password;

        if ($this->password->validate($password)) {
            return $password;
        }

        $errorMsg  = 'Password must have a minimum eight characters, at least one uppercase letter, ';
        $errorMsg .= 'one lowercase letter, one number and one special character';

        throw new RuntimeException($errorMsg);
    }

    public function validateUserExists(string $email): bool
    {
        $userDto = $this->getUser->byEmail($email);

        if ($userDto->userId === 0) {
            return false;
        }

        return true;
    }
}
