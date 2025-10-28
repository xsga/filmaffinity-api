<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Application\Services;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Shared\JWT\Application\Dto\PayloadDto;
use Xsga\FilmAffinityApi\Modules\Shared\JWT\Application\Services\JWTService;
use Xsga\FilmAffinityApi\Modules\Users\Application\Dto\GetTokenDto;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Model\User;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Services\UserLogin;

final class GetTokenService
{
    public function __construct(
        private LoggerInterface $logger,
        private UserLogin $userLogin,
        private JWTService $jwt,
        private string $secretKey,
        private int $tokenLifetime
    ) {
    }

    public function get(GetTokenDto $dto): string
    {
        $user  = $this->userLogin->login($dto->user, $dto->password);
        $token = $this->jwt->get($this->secretKey, $this->getTokenPayload($user));

        $this->logger->debug("Token for user '{$dto->user}' generated successfully");

        return $token;
    }

    private function getTokenPayload(User $user): PayloadDto
    {
        $payloadDto = new PayloadDto();

        $payloadDto->iat     = time();
        $payloadDto->exp     = time() + $this->tokenLifetime;
        $payloadDto->content = [
            'id'       => $user->userId(),
            'email'    => $user->email(),
            'password' => $user->password()
        ];

        return $payloadDto;
    }
}
