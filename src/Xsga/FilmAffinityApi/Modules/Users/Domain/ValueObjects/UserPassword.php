<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Domain\ValueObjects;

use Xsga\FilmAffinityApi\Modules\Users\Domain\Exceptions\InvalidPasswordException;

final class UserPassword
{
    private const string PATTERN       = "#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$#";
    private const string PASSWORD_ALG  = PASSWORD_DEFAULT;
    private const int PASSWORD_COST = 10;

    private string $hashed;

    public function __construct(string $password, bool $raw = true)
    {
        if (!$raw) {
            $this->hashed = $password;
            return;
        }

        $this->validateFormat($password);

        $this->hashed = password_hash($password, self::PASSWORD_ALG, ['cost' => self::PASSWORD_COST]);
    }

    private function validateFormat(string $password): void
    {
        if (preg_match(self::PATTERN, $password) !== 1) {
            throw new InvalidPasswordException('User password has not a valid format', 1015);
        }
    }

    public function value(): string
    {
        return $this->hashed;
    }
}
