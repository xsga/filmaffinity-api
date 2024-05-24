<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\ValueObjects;

use Xsga\FilmAffinityApi\Modules\Shared\ValueObjects\Exceptions\InvalidEmailException;

abstract class Email
{
    protected string $value;

    public function __construct(string $email)
    {
        $this->validateFormat($email);

        $this->value = $email;
    }

    private function validateFormat(string $email): void
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidEmailException("Email '$email' has not a valid format", 1016, null, [1 => $email]);
        }
    }

    public function value(): string
    {
        return $this->value;
    }
}
