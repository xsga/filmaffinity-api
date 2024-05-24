<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Errors\Infrastructure\Controllers\Responses;

final class ErrorResponse
{
    public int $code = 0;
    public int $httpCode = 0;
    public string $message = '';
}
