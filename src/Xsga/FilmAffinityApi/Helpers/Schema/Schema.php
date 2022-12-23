<?php

/**
 * Schema.
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
namespace Xsga\FilmAffinityApi\Helpers\Schema;

/**
 * Import dependencies.
 */
use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Exceptions\EmptySchemaException;
use Xsga\FilmAffinityApi\Exceptions\SchemaNotFoundException;

/**
 * Class Schema.
 */
final class Schema implements SchemaInterface
{
    /**
     * Base path.
     */
    private string $basePath;

    /**
     * Constructor.
     */
    public function __construct(private LoggerInterface $logger)
    {
        $this->basePath  = realpath(dirname(__FILE__, 3)) . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR;
        $this->basePath .= 'Schemas' . DIRECTORY_SEPARATOR . 'Api' . DIRECTORY_SEPARATOR;
    }

    /**
     * Get input schema.
     */
    public function getInputSchema(string $schemaName): string
    {
        $schemaLocation = $this->basePath .  'Input' . DIRECTORY_SEPARATOR .  $schemaName . '.json';

        return $this->getSchema($schemaLocation);
    }

    /**
     * Get output schema.
     */
    public function getOutputSchema(string $schemaName): string
    {
        $schemaLocation = $this->basePath . 'Output' . DIRECTORY_SEPARATOR .  $schemaName . '.json';

        return $this->getSchema($schemaLocation);
    }

    /**
     * Get schema.
     *
     * @throws SchemaNotFoundException Schema file not found.
     * @throws EmptySchemaException    Empty schema file.
     */
    private function getSchema(string $schemaLocation): string
    {
        if (!file_exists($schemaLocation)) {
            $errorMsg = 'Schema file not found';
            $this->logger->error($errorMsg);
            throw new SchemaNotFoundException($errorMsg, 1004);
        }//end if

        $schemaContent = file_get_contents($schemaLocation);

        if (empty($schemaContent)) {
            $errorMsg = "Empty schema file \"$schemaLocation\"";
            $this->logger->error($errorMsg);
            throw new EmptySchemaException($errorMsg, 1005);
        }//end if

        $this->logger->debug("Schema file \"$schemaLocation\" loaded sucessfully");

        return $schemaContent;
    }
}
