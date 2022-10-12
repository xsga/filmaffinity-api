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
     * Logger.
     *
     * @var LoggerInterface
     *
     * @access protected
     */
    protected $logger;

    /**
     * Schema.
     *
     * @var SchemaInterface
     *
     * @access private
     */
    private $schema;

    /**
     * JSON validator.
     *
     * @var JsonValidatorInterface
     *
     * @access private
     */
    private $jsonValidator;

    /**
     * Constructor.
     *
     * @param LoggerInterface        $logger        LoggerInterface instance.
     * @param SchemaInterface        $schema        SchemaInterface instance.
     * @param JsonValidatorInterface $jsonValidator JsonValidatorInterface instance.
     *
     * @access public
     */
    public function __construct(
        LoggerInterface $logger,
        SchemaInterface $schema,
        JsonValidatorInterface $jsonValidator
    ) {
        $this->logger        = $logger;
        $this->schema        = $schema;
        $this->jsonValidator = $jsonValidator;
    }

    /**
     * Validates JSON input file.
     *
     * @param string $jsonContent JSON content.
     * @param string $schemaName  Schema name.
     *
     * @return void
     *
     * @access protected
     */
    protected function validateJsonInput(string $jsonContent, string $schemaName): void
    {
        $schema = json_decode($this->schema->getInputSchema($schemaName));

        $this->validateJson($jsonContent, $schema);
    }

    /**
     * Validates JSON output file.
     *
     * @param string $jsonContent JSON content.
     * @param string $schemaName  Schema name.
     *
     * @return void
     *
     * @access protected
     */
    protected function validateJsonOutput(string $jsonContent, string $schemaName): void
    {
        $schema = json_decode($this->schema->getOutputSchema($schemaName));

        $this->validateJson($jsonContent, $schema);
    }

    /**
     * Validate JSON file.
     *
     * @param string $jsonContent JSON content.
     * @param object $schema      Schema object.
     *
     * @return void
     *
     * @throws JsonValidatorException Error validating JSON content.
     *
     * @access private
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
     *
     * @param Response $response   Response instance.
     * @param mixed    $data       Response data.
     * @param integer  $statusCode HTTPstatus code (default 200)
     *
     * @return Response
     *
     * @access protected
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
