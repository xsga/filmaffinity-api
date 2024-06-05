<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Services;

use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Throwable;
use Xsga\FilmAffinityApi\Modules\Shared\JWT\Application\Services\JWTService;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Dto\UserDataTokenDto;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Services\Helpers\AuthHeaderValidator;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Domain\SecurityTypes;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Services\GetUser;

final class TokenSecurityService
{
    public function __construct(
        private LoggerInterface $logger,
        private JWTService $jwt,
        private GetUser $getUser,
        private AuthHeaderValidator $authHeaderValidator
    ) {
    }

    public function apply(string $authHeader): ?UserDataTokenDto
    {
        $this->logger->debug('Applying TOKEN security');

        try {
            $authToken     = $this->authHeaderValidator->validate($authHeader, SecurityTypes::TOKEN);
            $userDataToken = $this->getUserDataToken($authToken);

            $this->validateTokenData($userDataToken);

            return $userDataToken;
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage());
            return null;
        }
    }

    private function getUserDataToken(string $authToken): UserDataTokenDto
    {
        $userDataToken = $this->jwt->decode($authToken);

        if ($userDataToken === null) {
            throw new InvalidArgumentException('JWT token not valid');
        }

        return $userDataToken;
    }

    private function validateTokenData(UserDataTokenDto $userDataToken): void
    {
        $user = $this->getUser->byEmail($userDataToken->email);

        if ($userDataToken->password !== $user->password()) {
            throw new InvalidArgumentException('User password from JWT token not equals from user');
        }
    }
}
