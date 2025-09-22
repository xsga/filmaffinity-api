<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\Slim\ErrorHandler;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Interfaces\ErrorHandlerInterface;
use Slim\Psr7\Response as Psr7Response;
use Throwable;
use Xsga\FilmAffinityApi\Modules\Errors\Application\Dto\ErrorDto;
use Xsga\FilmAffinityApi\Modules\Errors\Application\Services\GetErrorService;
use Xsga\FilmAffinityApi\Modules\Shared\Api\Application\Dto\ApiErrorDetailDto;
use Xsga\FilmAffinityApi\Modules\Shared\Api\Application\Dto\ApiErrorDto;
use Xsga\FilmAffinityApi\Modules\Shared\Api\Application\Dto\ApiResponseDto;
use Xsga\FilmAffinityApi\Modules\Shared\Exceptions\GenericException;

final class ErrorHandler implements ErrorHandlerInterface
{
    private const int ERROR_GENERIC = 1000;
    private const int ERROR_HTTP_REQUEST_METHOD_NOT_VALID = 1003;
    private const int ERROR_API_RESOURCE_NOT_FOUND = 1004;

    public function __construct(
        private LoggerInterface $logger,
        private GetErrorService $getError
    ) {
    }

    public function __invoke(
        Request $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): Response {
        $error       = $this->getError->get($this->getErrorCode($exception));
        $response    = new Psr7Response($error->httpCode);
        $responseDto = $this->getResponseDto($error, $response->getReasonPhrase(), $exception, $displayErrorDetails);

        $responseJson = json_encode($responseDto, JSON_UNESCAPED_UNICODE);

        if ($responseJson === false) {
            $this->logger->error("Error encoding JSON response: " . json_last_error_msg());
            $responseJson = '';
        }

        $response->getBody()->write($responseJson);

        $this->logger->error(sprintf("ERROR $error->code: %s", $exception->getMessage()));
        $this->logger->error($exception->__toString());

        return $response;
    }

    private function getErrorCode(Throwable $exception): int
    {
        $errorCode = match (true) {
            $exception instanceof HttpInternalServerErrorException => self::ERROR_GENERIC,
            $exception instanceof HttpMethodNotAllowedException => self::ERROR_HTTP_REQUEST_METHOD_NOT_VALID,
            $exception instanceof HttpNotFoundException => self::ERROR_API_RESOURCE_NOT_FOUND,
            default => (int)$exception->getCode()
        };

        return match ($errorCode) {
            0 => self::ERROR_GENERIC,
            default => $errorCode
        };
    }

    private function getResponseDto(
        ErrorDto $error,
        string $responseReasonPhrase,
        Throwable $exception,
        bool $displayErrorDetails
    ): ApiResponseDto {
        $responseDto = new ApiResponseDto();

        $responseDto->status     = "ERROR - $responseReasonPhrase";
        $responseDto->statusCode = $error->httpCode;
        $responseDto->response   = match ($displayErrorDetails) {
            true => $this->getErrorDetailDto($error, $exception),
            false => $this->getErrorDto($error, $exception)
        };

        return $responseDto;
    }

    private function getErrorDetailDto(ErrorDto $error, Throwable $exception): ApiErrorDetailDto
    {
        $errorDetailDto = new ApiErrorDetailDto();

        $errorDetailDto->code    = $error->code;
        $errorDetailDto->message = $this->getParsedMessage($error->message, $exception);
        $errorDetailDto->file    = $exception->getFile();
        $errorDetailDto->line    = $exception->getLine();
        $errorDetailDto->trace   = $exception->__toString();

        return $errorDetailDto;
    }

    private function getErrorDto(ErrorDto $error, Throwable $exception): ApiErrorDto
    {
        $errorDto = new ApiErrorDto();

        $errorDto->code    = $error->code;
        $errorDto->message = $this->getParsedMessage($error->message, $exception);

        return $errorDto;
    }

    private function getParsedMessage(string $message, Throwable $exception): string
    {
        if ($exception instanceof GenericException) {
            foreach ($exception->getParams() as $key => $value) {
                $message = str_replace('{' . (string)$key . '}', $value, $message);
            }
        }

        return $message;
    }
}
