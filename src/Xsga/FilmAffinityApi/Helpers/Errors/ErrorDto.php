<?php

/**
 * ErrorDto.
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
 * Class ErrorDto.
 */
class ErrorDto
{
    /**
     * Error code.
     */
    public int $code = -1;

    /**
     * HTTP code.
     */
    public int $httpCode = 500;

    /**
     * Error message.
     */
    public string $message = '';
}
