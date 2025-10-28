<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\JWT\Infrastructure\Services;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Log\LoggerInterface;
use stdClass;
use Xsga\FilmAffinityApi\Modules\Shared\JWT\Application\Dto\PayloadDto;
use Xsga\FilmAffinityApi\Modules\Shared\JWT\Application\Services\JWTService;

final class FirebaseJwtService implements JWTService
{
    private const string ALGORITHM = 'HS256';

    public function __construct(private LoggerInterface $logger)
    {
    }

    public function get(string $secretKey, PayloadDto $payloadDto): string
    {
        return JWT::encode($this->getFirebaseJWTPayload($payloadDto), $secretKey, self::ALGORITHM);
    }

    public function decode(string $secretKey, string $token): ?stdClass
    {
        try {
            $jwtStdClass = JWT::decode($token, new Key($secretKey, self::ALGORITHM));

            /** @var stdClass */
            return (object)$jwtStdClass->content;
        } catch (Exception $exception) {
            $this->logger->error(sprintf('JWT token validation failed => %s', $exception->getMessage()));
            return null;
        }
    }

    private function getFirebaseJWTPayload(PayloadDto $payloadDto): array
    {
        return [
            'iat'      => $payloadDto->iat,
            'exp'      => $payloadDto->exp,
            'content'  => $payloadDto->content
        ];
    }
}
