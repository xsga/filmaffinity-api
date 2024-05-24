<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\Api\Application\Dto;

final class ApiErrorDetailDto
{
    public int $code = 0;
    public string $message = '';
    public string $file = '';
    public int $line = 0;
    public string $trace = '';
}
