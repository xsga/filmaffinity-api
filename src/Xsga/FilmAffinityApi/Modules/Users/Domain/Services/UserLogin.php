<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Domain\Services;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Exceptions\UserCredentialsException;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Exceptions\UserDisabledException;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Exceptions\UserNotFoundException;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Model\User;

final class UserLogin
{
    private const int ERROR_USER_CREDENTIALS = 1007;

    public function __construct(
        private LoggerInterface $logger,
        private GetUser $getUser
    ) {
    }

    public function login(string $email, string $password): User
    {
        try {
            $user = $this->getUser->byEmail($email);
        } catch (UserNotFoundException) {
            $this->userCredentialExceptionExit($email);
        }

        $this->validateIfUserIsActive($user);
        $this->validateUserPassword($user, $password);

        $this->logger->info(sprintf("User '%s' logged successfully", $user->email()));

        return $user;
    }

    private function validateIfUserIsActive(User $user): void
    {
        if (!$user->status()) {
            $this->logger->error(sprintf("User '%s' is not active", $user->email()));
            $this->userCredentialExceptionExit($user->email());
        }
    }

    private function validateUserPassword(User $user, string $password): void
    {
        if (!password_verify($password, $user->password())) {
            $this->userCredentialExceptionExit($user->email());
        }
    }


    private function userCredentialExceptionExit(string $userEmail): never
    {
        $errorMsg = "Wrong user or password for user '$userEmail'";
        $this->logger->error($errorMsg);
        throw new UserCredentialsException($errorMsg, self::ERROR_USER_CREDENTIALS);
    }
}
