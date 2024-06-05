<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Errors\Domain\ValueObjects;

final class HttpCode
{
    private readonly int $httpCode;

    public function __construct(int $httpCode)
    {
        $this->httpCode = $httpCode;
    }

    public function value(): int
    {
        return $this->httpCode;
    }
}
