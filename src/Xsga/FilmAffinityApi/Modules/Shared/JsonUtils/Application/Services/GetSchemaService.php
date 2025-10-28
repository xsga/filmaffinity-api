<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Application\Services;

interface GetSchemaService
{
    public function get(string $schemaName): object;
}
