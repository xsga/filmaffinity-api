<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Infrastructure\Services;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Application\Services\GetSchemaService;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Domain\Exceptions\JsonSchemaException;

final class GetSchemaServiceImpl implements GetSchemaService
{
    private const int ERROR_JSON_SCHEMA_NOT_FOUND = 1011;

    /** @param string[] $locations */
    public function __construct(
        private LoggerInterface $logger,
        private array $locations
    ) {
    }

    public function get(string $schemaName): object
    {
        foreach ($this->locations as $location) {
            $schemaContent = $this->getContent("$location$schemaName.json");

            if ($schemaContent === false) {
                continue;
            }

            return $this->getSchema($schemaName, $schemaContent);
        }

        $errorMsg = "Error loading JSON schema file '$schemaName'";
        $this->logger->error($errorMsg);
        throw new JsonSchemaException($errorMsg, self::ERROR_JSON_SCHEMA_NOT_FOUND, null, [1 => $schemaName]);
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

    private function getSchema(string $schemaName, string $schemaContent): object
    {
        /** @var object|null $schema */
        $schema = json_decode($schemaContent);

        if ($schema === null) {
            $errorMsg = "Error decoding schema JSON file '$schemaName'";
            $this->logger->error($errorMsg);
            throw new JsonSchemaException($errorMsg, self::ERROR_JSON_SCHEMA_NOT_FOUND, null, [1 => $schemaName]);
        }

        return $schema;
    }
}
