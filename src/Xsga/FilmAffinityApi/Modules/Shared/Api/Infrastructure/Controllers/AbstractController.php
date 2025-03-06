<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\Api\Infrastructure\Controllers;

use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Shared\Api\Application\Dto\ApiResponseDto;
use Xsga\FilmAffinityApi\Modules\Shared\Api\Domain\Exceptions\InvalidRequestBodyException;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Application\Services\GetSchemaService;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Application\Services\JsonValidatorService;

abstract class AbstractController
{
    #[Inject]
    protected LoggerInterface $logger;

    #[Inject]
    private GetSchemaService $getSchema;

    #[Inject]
    private JsonValidatorService $jsonValidator;

    final protected function validateJson(string $jsonContent, string $schemaName): void
    {
        $schema = json_decode($this->getSchema->get($schemaName));

        if (!$this->jsonValidator->validate($jsonContent, $schema)) {
            $errorMsg = "Error validating request body against schema '$schemaName'";
            $this->logger->error($errorMsg);
            throw new InvalidRequestBodyException($errorMsg, 1010, null, [1 => $schemaName]);
        }
    }

    final protected function writeResponse(Response $response, mixed $data, int $statusCode = 200): Response
    {
        $responseDto = $this->getApiResponseDto($data, $statusCode);

        $response->getBody()->write(json_encode($responseDto, JSON_UNESCAPED_UNICODE));

        return $response;
    }

    private function getApiResponseDto(mixed $data, int $statusCode): ApiResponseDto
    {
        $responseDto = new ApiResponseDto();

        $responseDto->status     = 'OK';
        $responseDto->statusCode = $statusCode;
        $responseDto->response   = $data;

        return $responseDto;
    }
}
