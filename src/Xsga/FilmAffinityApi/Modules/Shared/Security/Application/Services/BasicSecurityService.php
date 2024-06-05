<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Services;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Dto\UserDataTokenDto;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Services\Helpers\AuthHeaderValidator;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Domain\SecurityTypes;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Model\User;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Services\UserLogin;

final class BasicSecurityService
{
    public function __construct(
        private LoggerInterface $logger,
        private UserLogin $userLogin,
        private AuthHeaderValidator $authHeaderValidator
    ) {
    }

    public function apply(string $authHeader): ?UserDataTokenDto
    {
        $this->logger->debug('Applying BASIC security');

        $basicAuthToken = $this->authHeaderValidator->validate($authHeader, SecurityTypes::BASIC);

        if (empty($basicAuthToken)) {
            $this->logger->error('Authorization header not valid');
            return null;
        }

        $userAndPass = $this->getUserAndPassFromBasicToken($basicAuthToken);

        if (empty($userAndPass)) {
            $this->logger->error('User and password token not valid');
            return null;
        }

        $user = $this->userLogin->login($userAndPass['user'], $userAndPass['password']);

        return $this->getUserDataToken($user);
    }

    private function getUserAndPassFromBasicToken(string $authorization): array
    {
        $userAndPass = explode(':', base64_decode($authorization));

        if (count($userAndPass) !== 2) {
            $this->logger->error('User and password token not valid');
            return [];
        }

        return ['user' => $userAndPass[0], 'password' => $userAndPass[1]];
    }

    private function getUserDataToken(User $user): UserDataTokenDto
    {
        $userDataToken           = new UserDataTokenDto();
        $userDataToken->userId   = $user->userId();
        $userDataToken->email    = $user->email();
        $userDataToken->password = $user->password();

        return $userDataToken;
    }
}
