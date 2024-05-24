<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Business\Users;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Helpers\JWT\JWTInterface;

final class GetToken
{
    public function __construct(
        private LoggerInterface $logger,
        private JWTInterface $jwt,
        private UserLogin $userLogin
    ) {
    }

    public function get(string $user, string $password): string
    {
        $userDto = $this->userLogin->login($user, $password);

        return $this->jwt->get($userDto->email);
    }
}
