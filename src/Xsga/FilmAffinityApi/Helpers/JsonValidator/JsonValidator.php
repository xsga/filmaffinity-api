<?php

/**
 * JsonValidator.
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
namespace Xsga\FilmAffinityApi\Helpers\JsonValidator;

/**
 * Import dependencies.
 */
use Exception;
use Psr\Log\LoggerInterface;
use Swaggest\JsonSchema\Schema;

/**
 * JsonValidator class.
 */
final class JsonValidator implements JsonValidatorInterface
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
     * Constructor.
     *
     * @param LoggerInterface $logger LoggerInterface instance.
     *
     * @access public
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Validate JSON content with schema.
     *
     * @param string $jsonContent JSON content.
     * @param object $schema      JSON schema.
     *
     * @return boolean
     *
     * @access public
     */
    public function validate(string $jsonContent, object $schema): bool
    {
        try {
            $val = Schema::import($schema);
            $val->in(json_decode($jsonContent));
        } catch (Exception $e) {
            $this->logger->error('Error validating JSON content');
            $this->logger->error($e->getMessage());
            return false;
        }//end try

        $this->logger->debug('JSON content validated successfully');

        return true;
    }
}
