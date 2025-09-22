<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Errors\Infrastructure\Repositories;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Errors\Domain\Model\Error;
use Xsga\FilmAffinityApi\Modules\Errors\Domain\Repositories\ErrorsRepository;
use Xsga\FilmAffinityApi\Modules\Errors\Infrastructure\Mappers\JsonErrorToError;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Application\Services\GetSchemaService;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Application\Services\JsonLoaderService;

final class JsonErrorsRepository implements ErrorsRepository
{
    private const string ERRORS_FILENAME = 'errors.json';
    private const string ERRORS_SCHEMA_NAME = 'errors.schema';

    public function __construct(
        private LoggerInterface $logger,
        private GetSchemaService $schema,
        private JsonLoaderService $jsonLoader,
        private string $errorsPath,
        private string $language,
        private JsonErrorToError $jsonErrorToModelMapper
    ) {
    }

    /** @return Error[] */
    public function getAllErrors(): array
    {
        $errors = $this->loadErrorsFile(self::ERRORS_FILENAME);

        if (empty($errors)) {
            $this->logger->error('Error loading errors files');
            return [];
        }

        $this->validateErrors($errors);

        return $this->jsonErrorToModelMapper->convertArray($errors, $this->language);
    }

    public function getError(int $code): ?Error
    {
        foreach ($this->getAllErrors() as $error) {
            if ($error->code() === $code) {
                return $error;
            }
        }

        $this->logger->warning("Error with code '$code' not found");

        return null;
    }

    private function loadErrorsFile(string $filename): array
    {
        return $this->jsonLoader->toArray(
            $this->errorsPath,
            $filename,
            $this->schema->get(self::ERRORS_SCHEMA_NAME)
        );
    }

    private function validateErrors(array $errors): void
    {
        $errorCodes = array_map(
            fn(array $error): string => (string)$error['code'],
            $errors
        );

        foreach (array_count_values($errorCodes) as $errorCode => $totalCodeItems) {
            if ($totalCodeItems > 1) {
                $this->logger->warning("Duplicated error with code '$errorCode'");
            }
        }
    }
}
