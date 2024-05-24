<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Commands\Validators;

use Psr\Log\LoggerInterface;
use RuntimeException;
use Xsga\FilmAffinityApi\Business\Users\GetUser;
use Xsga\FilmAffinityApi\Helpers\Validators\EmailValidator;

class ChangeUserStatusValidator
{
    public function __construct(
        private LoggerInterface $logger,
        private EmailValidator $emailValidator,
        private GetUser $getUser
    ) {
    }

    public function validateEmail(string $email): string
    {
        if ($this->emailValidator->validate($email)) {
            return $email;
        }

        throw new RuntimeException('E-mail not valid');
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
