<?php

/**
 * JsonValidatorInterface.
 *
 * This file contains the JsonValidatorInterface interface.
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
 * JsonValidatorInterface interface.
 */
interface JsonValidatorInterface
{
    /**
     * Validate JSON file with schema.
     */
    public function validate(string $jsonContent, object $schema): bool;
}
