<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Errors\Application\Mappers;

use Xsga\FilmAffinityApi\Modules\Errors\Application\Dto\ErrorDto;
use Xsga\FilmAffinityApi\Modules\Errors\Domain\Model\Error;

final class ErrorToErrorDto
{
    public function convert(Error $error): ErrorDto
    {
        $errorDto = new ErrorDto();

        $errorDto->code     = $error->code();
        $errorDto->httpCode = $error->httpCode();
        $errorDto->message  = $error->message();

        return $errorDto;
    }

    /**
     * @param Error[] $errors
     *
     * @return ErrorDto[]
     */
    public function convertArray(array $errors): array
    {
        $out = [];

        foreach ($errors as $error) {
            $out[] = $this->convert($error);
        }

        return $out;
    }
}
