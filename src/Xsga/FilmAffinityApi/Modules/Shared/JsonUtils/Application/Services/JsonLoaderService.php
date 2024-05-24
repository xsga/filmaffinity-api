<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Application\Services;

interface JsonLoaderService
{
    public function toArray(string $path, string $fileName, ?object $schema): array;
    public function toObject(string $path, string $fileName, ?object $schema): object;
}
