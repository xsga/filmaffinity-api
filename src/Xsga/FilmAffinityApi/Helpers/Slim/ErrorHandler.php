<?php

/**
 * ErrorHandler.
 *
 * PHP Version 8
 *
 * @author  xsga <parker@xsga.es>
 * @license MIT
 * @version 1.0.0
 */

/**
 * Namespace.
 */
namespace Xsga\FilmAffinityApi\Helpers\Slim;

/**
 * Import dependencies.
 */
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Interfaces\ErrorHandlerInterface;
use Slim\Psr7\Response as Psr7Response;
use Throwable;
use Xsga\FilmAffinityApi\Dto\ErrorDetailDto;
use Xsga\FilmAffinityApi\Dto\ErrorDto;
use Xsga\FilmAffinityApi\Dto\ResponseDto;
use Xsga\FilmAffinityApi\Helpers\Errors\ErrorDto as ErrorsErrorDto;
use Xsga\FilmAffinityApi\Helpers\Errors\ErrorsInterface;

/**
 * Class ErrorHandler.
 */
final class ErrorHandler implements ErrorHandlerInterface
{
    /**
     * Constructor.
     */
    public function __construct(
        private LoggerInterface $logger,
        private ErrorsInterface $errors
    ) {
    }

    /**
     * Invoke method.
     */
    public function __invoke(
        Request $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): Response {
        $error = $this->errors->getError($this->getErrorCode($exception));

        $response = new Psr7Response($error->httpCode);

        $responseDto             = new ResponseDto();
        $responseDto->status     = 'ERROR - ' . $response->getReasonPhrase();
        $responseDto->statusCode = $error->httpCode;
        $responseDto->response   = $this->getErrorDto($displayErrorDetails, $error, $exception);

        $response->getBody()->write(json_encode($responseDto, JSON_UNESCAPED_UNICODE));

        $this->logger->error('ERROR ' . $error->code . ': ' . $exception->getMessage());
        $this->logger->error($exception->__toString());

        return $response;
    }

    /**
     * Get error code.
     */
    private function getErrorCode(Throwable $exception): int|string
    {
        $code = 0;

        if ($exception instanceof HttpMethodNotAllowedException) {
            $code = 1002;
        }//end if

        if ($exception instanceof HttpNotFoundException) {
            $code = 1003;
        }//end if

        if ($exception instanceof HttpInternalServerErrorException) {
            $code = 1001;
        }//end if

        if ($code === 0) {
            $code = $exception->getCode();
        }//end if

        return $code;
    }

    /**
     * Get error DTO.
     */
    private function getErrorDto(
        bool $displayErrorDetails,
        ErrorsErrorDto $error,
        Throwable $exception
    ): ErrorDetailDto|ErrorDto {
        if ($displayErrorDetails) {
            $errorDto          = new ErrorDetailDto();
            $errorDto->code    = $error->code;
            $errorDto->message = $error->message;
            $errorDto->file    = $exception->getFile();
            $errorDto->line    = $exception->getLine();
            $errorDto->trace   = $exception->__toString();

            return $errorDto;
        }//end if

        $errorDto          = new ErrorDto();
        $errorDto->code    = $error->code;
        $errorDto->message = $error->message;

        return $errorDto;
    }
}
