<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Errors\Infrastructure\Mappers\Impl;

use Xsga\FilmAffinityApi\Modules\Errors\Domain\Model\Error;
use Xsga\FilmAffinityApi\Modules\Errors\Infrastructure\Mappers\JsonErrorToError;

final class JsonErrorToErrorImpl implements JsonErrorToError
{
    public function convert(array $errorData, string $language): Error
    {
        return new Error(
            $errorData['code'],
            $errorData['http'],
            $errorData['description'][$language]
        );
    }

    /**
     * @return Error[]
     */
    public function convertArray(array $errorsData, string $language): array
    {
        $out = [];

        foreach ($errorsData as $errorData) {
            $out[] = $this->convert($errorData, $language);
        }

        return $out;
    }
}
