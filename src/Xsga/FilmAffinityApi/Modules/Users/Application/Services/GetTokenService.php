<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Application\Services;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Shared\JWT\Application\Services\JWTService;
use Xsga\FilmAffinityApi\Modules\Users\Application\Mappers\UserToUserDto;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Services\UserLogin;

final class GetTokenService
{
    public function __construct(
        private LoggerInterface $logger,
        private UserLogin $userLogin,
        private JWTService $jwt,
        private UserToUserDto $mapper
    ) {
    }

    public function get(string $email, string $password): string
    {
        $user    = $this->userLogin->login($email, $password);
        $userDto = $this->mapper->convert($user);
        $token   = $this->jwt->get($userDto);

        $this->logger->info("Token for user '$email' generated successfully");

        return $token;
    }
}
