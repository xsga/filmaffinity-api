<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Errors\Application\Mappers;

use Xsga\FilmAffinityApi\Modules\Errors\Application\Dto\ErrorDto;
use Xsga\FilmAffinityApi\Modules\Errors\Domain\Model\Error;

interface ErrorToErrorDto
{
    public function convert(Error $error): ErrorDto;

    /**
     * @param Error[] $errors
     *
     * @return ErrorDto[]
     */
    public function convertArray(array $errors): array;
}
