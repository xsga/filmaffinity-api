<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Infrastructure\Services;

use Exception;
use Psr\Log\LoggerInterface;
use Swaggest\JsonSchema\Schema;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Application\Services\JsonValidatorService;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Domain\Exceptions\JsonDecodeException;

final class SwaggestJsonValidationService implements JsonValidatorService
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function validate(string $jsonContent, object $schema): bool
    {
        try {
            $validation = Schema::import($schema);

            /** @var object|null $jsonDecoded */
            $jsonDecoded = json_decode($jsonContent);

            if ($jsonDecoded === null) {
                throw new JsonDecodeException("Error decoding JSON content: " . json_last_error_msg());
            }

            $validation->in($jsonDecoded);
            return true;
        } catch (Exception $exception) {
            $this->logger->error('Error validating JSON content');
            $this->logger->error($exception->getMessage());
            return false;
        }
    }
}
