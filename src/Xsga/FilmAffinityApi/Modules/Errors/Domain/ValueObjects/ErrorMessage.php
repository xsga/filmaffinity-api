<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Errors\Domain\ValueObjects;

final class ErrorMessage
{
    private readonly string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function value(): string
    {
        return $this->message;
    }
}
