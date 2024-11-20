<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\JWT\Infrastructure\Services;

use DomainException;
use Exception;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT as FirebaseJWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Psr\Log\LoggerInterface;
use UnexpectedValueException;
use Xsga\FilmAffinityApi\Modules\Shared\JWT\Application\Services\JWTService;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Dto\UserDataTokenDto;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Model\User;

final class FirebaseJwtService implements JWTService
{
    private const string ALGORITHM = 'HS256';

    public function __construct(
        private LoggerInterface $logger,
        private string $secretKey,
        private int $tokenLifetime
    ) {
    }

    public function get(User $user): string
    {
        $timestamp = time();

        $payload = [
            'iat'      => $timestamp,
            'exp'      => $timestamp + $this->tokenLifetime,
            'userData' => [
                'id'       => $user->userId(),
                'email'    => $user->email(),
                'password' => $user->password()
            ]
        ];

        return FirebaseJWT::encode($payload, $this->secretKey, self::ALGORITHM);
    }

    public function decode(string $token): ?UserDataTokenDto
    {
        try {
            $jwtObj = FirebaseJWT::decode($token, new Key($this->secretKey, self::ALGORITHM));

            $userTokenData = new UserDataTokenDto();

            $userTokenData->userId = match (isset($jwtObj->userData->id)) {
                true => $jwtObj->userData->id,
                false => ''
            };

            $userTokenData->email = match (isset($jwtObj->userData->email)) {
                true => $jwtObj->userData->email,
                false => ''
            };

            $userTokenData->password = match (isset($jwtObj->userData->password)) {
                true => $jwtObj->userData->password,
                false => ''
            };

            return $userTokenData;
        } catch (Exception $exception) {
            $this->logError($exception);
            return null;
        }
    }

    private function logError(Exception $exception): void
    {
        $this->logger->error('JWT token validation failed');

        match (true) {
            $exception instanceof ExpiredException => $this->logger->error('Expired JWT token'),
            $exception instanceof BeforeValidException => $this->logger->error('Not active JWT token'),
            $exception instanceof SignatureInvalidException => $this->logger->error('Invalid signature JWT token'),
            $exception instanceof DomainException => $this->logger->error('Malformed JWT token'),
            $exception instanceof UnexpectedValueException => $this->logger->error('Invalid JWT token'),
            default => $this->logger->error('Generic JWT error')
        };

        $this->logger->error($exception->__toString());
    }
}
