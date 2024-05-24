<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Errors\Infrastructure\Mappers;

use Xsga\FilmAffinityApi\Modules\Errors\Domain\Model\Error;

interface JsonErrorToError
{
    public function convert(array $errorData, string $language): Error;

    /**
     * @return Error[]
     */
    public function convertArray(array $errorsData, string $language): array;
}
