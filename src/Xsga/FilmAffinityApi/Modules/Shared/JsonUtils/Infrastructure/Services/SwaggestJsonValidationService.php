<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Infrastructure\Services;

use Exception;
use Psr\Log\LoggerInterface;
use Swaggest\JsonSchema\Schema;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Application\Services\JsonValidatorService;

final class SwaggestJsonValidationService implements JsonValidatorService
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function validate(string $jsonContent, object $schema): bool
    {
        try {
            $validation = Schema::import($schema);
            $validation->in(json_decode($jsonContent));
            return true;
        } catch (Exception $exception) {
            $this->logger->error('Error validating JSON content');
            $this->logger->error($exception->getMessage());
            return false;
        }
    }
}
