<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Infrastructure\Services;

use Psr\Log\LoggerInterface;
use stdClass;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Application\Services\JsonLoaderService;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Application\Services\JsonValidatorService;

final class JsonLoaderServiceImpl implements JsonLoaderService
{
    private bool $outputArray = false;

    public function __construct(
        private LoggerInterface $logger,
        private JsonValidatorService $validator
    ) {
    }

    public function toArray(string $path, string $fileName, ?object $schema = null): array
    {
        $this->outputArray = true;

        return (array)$this->loadJson($path, $fileName, $schema);
    }

    public function toObject(string $path, string $fileName, ?object $schema = null): object
    {
        return (object)$this->loadJson($path, $fileName, $schema);
    }

    private function loadJson(string $path, string $fileName, ?object $schema): array|object
    {
        $fileLocation = $path . $fileName;

        if (!file_exists($fileLocation)) {
            $this->logger->error("JSON file '$fileLocation' not found");
            return $this->getEmptyOutput();
        }

        $jsonFile = file_get_contents($fileLocation);

        if (!$this->validate($fileName, $jsonFile, $schema)) {
            return $this->getEmptyOutput();
        }

        $content = json_decode($jsonFile, $this->outputArray);

        if (empty($content)) {
            $this->logger->error("JSON file '$fileLocation' it's empty");
            return $this->getEmptyOutput();
        }

        return $content;
    }

    private function getEmptyOutput(): array|object
    {
        return match ($this->outputArray) {
            true => [],
            default => new stdClass()
        };
    }

    private function validate(string $fileName, string $jsonFile, ?object $schema): bool
    {
        if ($schema === null) {
            return true;
        }

        if ($this->validator->validate($jsonFile, $schema)) {
            return true;
        }

        $this->logger->error("Error validating JSON file '$fileName'");
        return false;
    }
}
