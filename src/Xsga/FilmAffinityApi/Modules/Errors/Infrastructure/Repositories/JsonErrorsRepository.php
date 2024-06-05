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
    private string $errorsFilename = 'errors.json';
    private string $errorsSchemaName = 'errors.schema';

    public function __construct(
        private LoggerInterface $logger,
        private GetSchemaService $schema,
        private JsonLoaderService $jsonLoader,
        private string $errorsPath,
        private string $language,
        private JsonErrorToError $jsonErrorToModelMapper
    ) {
    }

    /**
     * @return Error[]
     */
    public function getAllErrors(): array
    {
        $errors = $this->jsonLoader->toArray(
            $this->errorsPath,
            $this->errorsFilename,
            json_decode($this->schema->get($this->errorsSchemaName))
        );

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

    private function validateErrors(array $errors): void
    {
        $errorCodes = [];

        foreach ($errors as $error) {
            $errorCodes[] = $error['code'];
        }

        foreach (array_count_values($errorCodes) as $errorCode => $totalCodeItems) {
            if ($totalCodeItems > 1) {
                $this->logger->warning("Duplicated error with code '$errorCode'");
            }
        }
    }
}
