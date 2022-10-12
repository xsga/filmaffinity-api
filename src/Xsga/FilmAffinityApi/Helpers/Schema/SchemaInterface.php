<?php

/**
 * SchemaInterface.
 *
 * This file contains the Schema interface.
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
use Exception;

/**
 * Schema interface.
 */
interface SchemaInterface
{
    /**
     * Get API input schema.
     *
     * @param string $schemaName Schema file name.
     *
     * @return string
     *
     * @throws Exception Error loading schema.
     *
     * @access public
     */
    public function getInputSchema(string $schemaName): string;

    /**
     * Get API output schema.
     *
     * @param string $schemaName Schema file name.
     *
     * @return string
     *
     * @throws Exception Error loading schema.
     *
     * @access public
     */
    public function getOutputSchema(string $schemaName): string;
}
