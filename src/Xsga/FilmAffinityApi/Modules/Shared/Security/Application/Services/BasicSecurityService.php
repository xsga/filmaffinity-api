<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Services;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Dto\BasicUserTokenDto;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Dto\UserDataTokenDto;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Services\Helpers\GetAuthHeaderToken;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Domain\SecurityTypes;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Model\User;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Services\UserLogin;

final class BasicSecurityService
{
    public function __construct(
        private LoggerInterface $logger,
        private UserLogin $userLogin,
        private GetAuthHeaderToken $getAuthHeaderToken
    ) {
    }

    public function apply(string $authHeader): ?UserDataTokenDto
    {
        $basicAuthToken = $this->getAuthHeaderToken->get($authHeader, SecurityTypes::BASIC);

        if (empty($basicAuthToken)) {
            $this->logger->error('HTTP authorization header not valid');
            return null;
        }

        $basicUserTokenDto = $this->getDataFromBasicToken($basicAuthToken);

        if ($basicUserTokenDto === null) {
            $this->logger->error('BASIC auth token not valid');
            return null;
        }

        $user = $this->userLogin->login($basicUserTokenDto->name, $basicUserTokenDto->password);

        return $this->getUserDataToken($user);
    }

    private function getDataFromBasicToken(string $basicAuthToken): ?BasicUserTokenDto
    {
        $basicAuthTokenData = explode(':', base64_decode($basicAuthToken));

        if (count($basicAuthTokenData) !== 2) {
            return null;
        }

        return $this->getBasicTokenDto($basicAuthTokenData);
    }

    private function getBasicTokenDto(array $basicAuthTokenData): BasicUserTokenDto
    {
        $basicUserTokenDto = new BasicUserTokenDto();
        $basicUserTokenDto->name     = $basicAuthTokenData[0];
        $basicUserTokenDto->password = $basicAuthTokenData[1];

        return $basicUserTokenDto;
    }

    private function getUserDataToken(User $user): UserDataTokenDto
    {
        $userDataToken = new UserDataTokenDto();
        $userDataToken->userId   = $user->userId();
        $userDataToken->email    = $user->email();
        $userDataToken->password = $user->password();

        return $userDataToken;
    }
}
