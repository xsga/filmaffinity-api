<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Application\Services;

interface JsonValidatorService
{
    public function validate(string $jsonContent, object $schema): bool;
}
