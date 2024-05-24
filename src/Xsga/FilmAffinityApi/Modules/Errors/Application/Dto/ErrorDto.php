<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Errors\Application\Dto;

final class ErrorDto
{
    public int $code = 0;
    public int $httpCode = 500;
    public string $message = 'Unknown error';
}
