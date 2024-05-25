<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Errors\Infrastructure\Mappers;

use Xsga\FilmAffinityApi\Modules\Errors\Domain\Model\Error;

final class JsonErrorToError
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
