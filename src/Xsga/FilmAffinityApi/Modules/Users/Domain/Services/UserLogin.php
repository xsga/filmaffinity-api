<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Domain\Services;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\App\Domain\Model\User;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Exceptions\Users\UserCredentialsException;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Exceptions\Users\UserDisabledException;

final class UserLogin
{
    public function __construct(
        private LoggerInterface $logger,
        private GetUser $getUser
    ) {
    }

    public function login(string $email, string $password): User
    {
        $user = $this->getUser->byEmail($email);

        $this->validateIfUserIsActive($user);
        $this->validateUserPassword($user, $password);

        return $user;
    }

    private function validateIfUserIsActive(User $user): void
    {
        if (!$user->status()) {
            $errorMsg = "User '" . $user->email() . "' is not active";
            $this->logger->error($errorMsg);
            throw new UserDisabledException($errorMsg, 1006, null, [1 => $user->email()]);
        }
    }

    private function validateUserPassword(User $user, string $password): void
    {
        if (!password_verify($password, $user->password())) {
            $errorMsg = "Wrong password for user '" . $user->email() . "'";
            $this->logger->error($errorMsg);
            throw new UserCredentialsException($errorMsg, 1007);
        }
    }
}
