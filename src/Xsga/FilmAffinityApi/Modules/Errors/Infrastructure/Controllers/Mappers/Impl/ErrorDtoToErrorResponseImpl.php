<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Errors\Infrastructure\Controllers\Mappers\Impl;

use Xsga\FilmAffinityApi\Modules\Errors\Application\Dto\ErrorDto;
use Xsga\FilmAffinityApi\Modules\Errors\Infrastructure\Controllers\Mappers\ErrorDtoToErrorResponse;
use Xsga\FilmAffinityApi\Modules\Errors\Infrastructure\Controllers\Responses\ErrorResponse;

final class ErrorDtoToErrorResponseImpl implements ErrorDtoToErrorResponse
{
    public function convert(ErrorDto $errorDto): ErrorResponse
    {
        $errorResponse = new ErrorResponse();

        $errorResponse->code     = $errorDto->code;
        $errorResponse->httpCode = $errorDto->httpCode;
        $errorResponse->message  = $errorDto->message;

        return $errorResponse;
    }

    /**
     * @param ErrorDto[] $errorDto
     *
     * @return ErrorResponse[]
     */
    public function convertArray(array $errorDto): array
    {
        $out = [];

        foreach ($errorDto as $error) {
            $out[] = $this->convert($error);
        }

        return $out;
    }
}
