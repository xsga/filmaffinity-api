<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\ValueObjects;

use Xsga\FilmAffinityApi\Modules\Shared\ValueObjects\Exceptions\InvalidCodeException;

abstract class Code
{
    private const int MAX_LENGTH = 50;
    private const int MIN_LENGTH = 2;

    protected readonly string $value;

    public function __construct(string $code)
    {
        $this->validateLength($code);

        $this->value = $code;
    }

    private function validateLength(string $code): void
    {
        if (strlen($code) < self::MIN_LENGTH || strlen($code) > self::MAX_LENGTH) {
            throw new InvalidCodeException("Code '$code' has not a valid length", 1050, null, [1 => $code]);
        }
    }

    public function value(): string
    {
        return $this->value;
    }
}
