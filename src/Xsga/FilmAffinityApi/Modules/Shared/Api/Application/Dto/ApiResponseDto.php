<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\Api\Application\Dto;

final class ApiResponseDto
{
    public string $status = '';
    public int $statusCode = -1;
    public mixed $response = '';
}
