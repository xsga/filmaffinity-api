<?php

/**
 * ErrorsInterface.
 *
 * This file contains the ErrorsInterface interface.
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
namespace Xsga\FilmAffinityApi\Helpers\Errors;

/**
 * ErrorsInterface.
 */
interface ErrorsInterface
{
    /**
     * Get error.
     *
     * @param int $code Error code.
     *
     * @return ErrorDto
     *
     * @access public
     */
    public function getError(int $code): ErrorDto;
}
