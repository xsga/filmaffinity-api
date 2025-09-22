<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\Api\Infrastructure\Controllers;

use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Shared\Api\Application\Dto\ApiResponseDto;
use Xsga\FilmAffinityApi\Modules\Shared\Api\Domain\Exceptions\InvalidRequestBodyException;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Application\Services\GetSchemaService;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Application\Services\JsonValidatorService;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Dto\UserDataTokenDto;

abstract class AbstractController
{
    private const int ERROR_VALIDATING_JSON = 1010;

    private const int HTTP_RESPONSE_200 = 200;

    #[Inject]
    protected LoggerInterface $logger;

    #[Inject]
    private GetSchemaService $getSchema;

    #[Inject]
    private JsonValidatorService $jsonValidator;

    final protected function validateJson(string $jsonContent, string $schemaName): void
    {
        if (!$this->jsonValidator->validate($jsonContent, $this->getSchema->get($schemaName))) {
            $errorMsg = "Error validating request body against schema '$schemaName'";
            $this->logger->error($errorMsg);
            throw new InvalidRequestBodyException($errorMsg, self::ERROR_VALIDATING_JSON, null, [1 => $schemaName]);
        }
    }

    final protected function writeResponse(Response $response, mixed $data, ?int $statusCode = null): Response
    {
        $body = json_encode(
            $this->getApiResponseDto($data, $statusCode ?? self::HTTP_RESPONSE_200),
            JSON_UNESCAPED_UNICODE
        );

        $response->getBody()->write($body === false ? '' : $body);

        return $response;
    }

    final protected function getUserDataToken(Request $request): UserDataTokenDto
    {
        /** @var UserDataTokenDto|null $userDataToken */
        $userDataToken = $request->getAttribute('userDataToken', null);

        if ($userDataToken instanceof UserDataTokenDto) {
            return $userDataToken;
        }

        return new UserDataTokenDto();
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
