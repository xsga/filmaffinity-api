<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Errors\Domain\Model;

use Xsga\FilmAffinityApi\Modules\Errors\Domain\ValueObjects\ErrorCode;
use Xsga\FilmAffinityApi\Modules\Errors\Domain\ValueObjects\ErrorMessage;
use Xsga\FilmAffinityApi\Modules\Errors\Domain\ValueObjects\HttpCode;

final class Error
{
    private readonly ErrorCode $code;
    private readonly HttpCode $httpCode;
    private readonly ErrorMessage $message;

    public function __construct(int $code, int $httpCode, string $message)
    {
        $this->code     = new ErrorCode($code);
        $this->httpCode = new HttpCode($httpCode);
        $this->message  = new ErrorMessage($message);
    }

    public function code(): int
    {
        return $this->code->value();
    }

    public function httpcode(): int
    {
        return $this->httpCode->value();
    }

    public function message(): string
    {
        return $this->message->value();
    }
}
