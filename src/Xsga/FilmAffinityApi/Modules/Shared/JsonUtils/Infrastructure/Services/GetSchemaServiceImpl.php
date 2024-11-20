<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Infrastructure\Services;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Application\Services\GetSchemaService;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Domain\Exceptions\JsonSchemaException;

final class GetSchemaServiceImpl implements GetSchemaService
{
    public function __construct(
        private LoggerInterface $logger,
        private string $location
    ) {
    }

    public function get(string $schemaName): string
    {
        $schemaContent = $this->getContent($this->location . $schemaName . '.json');

        if (is_string($schemaContent)) {
            return $schemaContent;
        }

        $errorMsg = "Error loading JSON schema file '$schemaName'";
        $this->logger->error($errorMsg);
        throw new JsonSchemaException($errorMsg, 1011, null, [1 => $schemaName]);
    }

    private function getContent(string $fileLocation): string|false
    {
        if (!file_exists($fileLocation)) {
            $this->logger->error("Schema file not found");
            return false;
        }

        $schemaContent = file_get_contents($fileLocation);

        if ($schemaContent === '') {
            $this->logger->error("Schema file empty");
            return false;
        }

        return $schemaContent;
    }
}
