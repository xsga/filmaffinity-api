<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Business\Users;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Dto\UserDto as UserDto;
use Xsga\FilmAffinityApi\Exceptions\UserDisabledException;
use Xsga\FilmAffinityApi\Exceptions\UserLoginException;
use Xsga\FilmAffinityApi\Exceptions\UserNotFoundException;
use Xsga\FilmAffinityApi\Helpers\Password\PasswordInterface;

final class UserLogin
{
    public function __construct(
        private LoggerInterface $logger,
        private GetUser $getUser,
        private PasswordInterface $passUtils
    ) {
    }

    public function login(string $user, string $password): UserDto
    {
        $userData = $this->getUser->byEmail($user);

        if ($userData->id === 0) {
            $errorMsg = "User '$user' not found";
            $this->logger->error($errorMsg);
            throw new UserNotFoundException($errorMsg, 1016);
        }

        if ($userData->enabled === 0) {
            $errorMsg = "User '$user' disabled";
            $this->logger->error($errorMsg);
            throw new UserDisabledException($errorMsg, 1017);
        }

        if (!$this->passUtils->verify($password, $userData->password)) {
            $errorMsg = 'Wrong user password';
            $this->logger->error($errorMsg);
            throw new UserLoginException($errorMsg, 1018);
        }

        $this->logger->debug("User '$user' login successfully");

        return $userData;
    }
}
