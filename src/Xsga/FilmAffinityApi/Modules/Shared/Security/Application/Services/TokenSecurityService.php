<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Services;

use Psr\Log\LoggerInterface;
use stdClass;
use Throwable;
use Xsga\FilmAffinityApi\Modules\Shared\JWT\Application\Services\JWTService;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Dto\UserDataTokenDto;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Services\Helpers\GetAuthHeaderToken;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Domain\Exceptions\InvalidAuthHeaderException;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Domain\SecurityTypes;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Services\GetUser;

final class TokenSecurityService
{
    public function __construct(
        private LoggerInterface $logger,
        private JWTService $jwtService,
        private GetUser $getUser,
        private GetAuthHeaderToken $getAuthHeaderToken,
        private string $secretKey
    ) {
    }

    public function apply(string $authHeader): ?UserDataTokenDto
    {
        try {
            $authToken     = $this->getAuthHeaderToken->get($authHeader, SecurityTypes::TOKEN);
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
        $jwtObj = $this->jwtService->decode($this->secretKey, $authToken);

        if ($jwtObj === null) {
            throw new InvalidAuthHeaderException('JWT token not valid');
        }

        return $this->getUserTokenFromJwtObj($jwtObj);
    }

    private function getUserTokenFromJwtObj(stdClass $jwtObj): UserDataTokenDto
    {
        $userTokenData = new UserDataTokenDto();

        $userTokenData->userId   = (int)$jwtObj->id;
        $userTokenData->email    = (string)$jwtObj->email;
        $userTokenData->password = (string)$jwtObj->password;

        return $userTokenData;
    }

    private function validateTokenData(UserDataTokenDto $userDataToken): void
    {
        $user = $this->getUser->byEmail($userDataToken->email);

        if ($userDataToken->password !== $user->password()) {
            throw new InvalidAuthHeaderException('JWT token user password is not the same as user');
        }
    }
}
