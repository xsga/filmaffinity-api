<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Errors\Infrastructure\Controllers\Mappers;

use Xsga\FilmAffinityApi\Modules\Errors\Application\Dto\ErrorDto;
use Xsga\FilmAffinityApi\Modules\Errors\Infrastructure\Controllers\Responses\ErrorResponse;

interface ErrorDtoToErrorResponse
{
    public function convert(ErrorDto $errorDto): ErrorResponse;

    /**
     * @param ErrorDto[] $errorDto
     *
     * @return ErrorResponse[]
     */
    public function convertArray(array $errorDto): array;
}
