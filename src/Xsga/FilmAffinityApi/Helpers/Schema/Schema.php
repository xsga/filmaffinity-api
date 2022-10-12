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
     * Logger.
     *
     * @var LoggerInterface
     *
     * @access private
     */
    private $logger;

    /**
     * Base path.
     *
     * @var string
     *
     * @access private
     */
    private $basePath;

    /**
     * Constructor.
     *
     * @param LoggerInterface $logger LoggerInterface instance.
     *
     * @access public
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger    = $logger;
        $this->basePath  = realpath(dirname(__FILE__, 3)) . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR;
        $this->basePath .= 'Schemas' . DIRECTORY_SEPARATOR . 'Api' . DIRECTORY_SEPARATOR;
    }

    /**
     * Get input schema.
     *
     * @param string $schemaName Schema name.
     *
     * @return string
     *
     * @access public
     */
    public function getInputSchema(string $schemaName): string
    {
        $schemaLocation = $this->basePath .  'Input' . DIRECTORY_SEPARATOR .  $schemaName . '.json';

        return $this->getSchema($schemaLocation);
    }

    /**
     * Get output schema.
     *
     * @param string $schemaName Schema name.
     *
     * @return string
     *
     * @access public
     */
    public function getOutputSchema(string $schemaName): string
    {
        $schemaLocation = $this->basePath . 'Output' . DIRECTORY_SEPARATOR .  $schemaName . '.json';

        return $this->getSchema($schemaLocation);
    }

    /**
     * Get schema.
     *
     * @param string $schemaLocation Schema location.
     *
     * @return string
     *
     * @throws SchemaNotFoundException Schema file not found.
     * @throws EmptySchemaException    Empty schema file.
     *
     * @access private
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
