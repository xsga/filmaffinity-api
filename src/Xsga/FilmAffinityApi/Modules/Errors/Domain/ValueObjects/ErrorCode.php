<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Errors\Domain\ValueObjects;

final class ErrorCode
{
    private readonly int $code;

    public function __construct(int $code)
    {
        $this->code = $code;
    }

    public function value(): int
    {
        return $this->code;
    }
}
