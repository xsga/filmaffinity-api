<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Domain\ValueObjects;

use Xsga\FilmAffinityApi\Modules\Users\Domain\Exceptions\Users\InvalidNameException;

final class UserName
{
    private const int MIN_LENGTH = 3;

    private string $name;

    public function __construct(string $name)
    {
        $this->validateFormat($name);

        $this->name = $name;
    }

    private function validateFormat(string $name): void
    {
        if (strlen($name) < self::MIN_LENGTH) {
            throw new InvalidNameException("User name '$name' has not a valid format", 1018, null, [1 => $name]);
        }
    }

    public function value(): string
    {
        return $this->name;
    }
}
