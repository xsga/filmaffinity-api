<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\JWT\Application\Services;

use stdClass;
use Xsga\FilmAffinityApi\Modules\Shared\JWT\Application\Dto\PayloadDto;

interface JWTService
{
    public function get(string $secretKey, PayloadDto $payloadDto): string;
    public function decode(string $secretKey, string $token): ?stdClass;
}
