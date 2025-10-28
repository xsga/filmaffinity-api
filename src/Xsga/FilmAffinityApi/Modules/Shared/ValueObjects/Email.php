<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\ValueObjects;

use Xsga\FilmAffinityApi\Modules\Shared\ValueObjects\Exceptions\InvalidEmailException;

abstract class Email
{
    private const int ERROR_EMAIL_NOT_VALID = 1016;

    protected string $value;

    public function __construct(string $email)
    {
        $this->validateFormat($email);

        $this->value = $email;
    }

    private function validateFormat(string $email): void
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidEmailException(
                "Email '$email' has not a valid format",
                self::ERROR_EMAIL_NOT_VALID,
                null,
                [1 => $email]
            );
        }
    }

    public function value(): string
    {
        return $this->value;
    }
}
