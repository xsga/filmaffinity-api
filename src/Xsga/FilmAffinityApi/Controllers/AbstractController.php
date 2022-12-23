<?php

/**
 * AbstractController.
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
namespace Xsga\FilmAffinityApi\Controllers;

/**
 * Import dependencies.
 */
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Dto\ResponseDto;
use Xsga\FilmAffinityApi\Exceptions\JsonValidatorException;
use Xsga\FilmAffinityApi\Helpers\JsonValidator\JsonValidatorInterface;
use Xsga\FilmAffinityApi\Helpers\Schema\SchemaInterface;

/**
 * AbstractController.
 */
abstract class AbstractController
{
    /**
     * Constructor.
     */
    public function __construct(
        protected LoggerInterface $logger,
        private SchemaInterface $schema,
        private JsonValidatorInterface $jsonValidator
    ) {
    }

    /**
     * Validates JSON input file.
     */
    protected function validateJsonInput(string $jsonContent, string $schemaName): void
    {
        $schema = json_decode($this->schema->getInputSchema($schemaName));

        $this->validateJson($jsonContent, $schema);
    }

    /**
     * Validates JSON output file.
     */
    protected function validateJsonOutput(string $jsonContent, string $schemaName): void
    {
        $schema = json_decode($this->schema->getOutputSchema($schemaName));

        $this->validateJson($jsonContent, $schema);
    }

    /**
     * Validate JSON file.
     *
     * @throws JsonValidatorException Error validating JSON content.
     */
    private function validateJson(string $jsonContent, object $schema): void
    {
        if ($this->jsonValidator->validate($jsonContent, $schema)) {
            $this->logger->debug('JSON file validated successfully');
            return;
        }//end if

        $errorMsg = 'JSON file validation failed';
        $this->logger->error($errorMsg);
        throw new JsonValidatorException($errorMsg, 1007);
    }

    /**
     * Write response body.
     */
    protected function writeResponse(Response $response, mixed $data, int $statusCode = 200): Response
    {
        $responseDto             = new ResponseDto();
        $responseDto->status     = 'OK';
        $responseDto->statusCode = $statusCode;
        $responseDto->response   = $data;

        $response->getBody()->write(json_encode($responseDto, JSON_UNESCAPED_UNICODE));

        return $response;
    }
}
