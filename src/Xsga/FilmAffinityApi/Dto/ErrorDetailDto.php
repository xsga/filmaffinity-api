<?php

/**
 * ErrorDetailDto.
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
namespace Xsga\FilmAffinityApi\Dto;

/**
 * Class ErrorDetailDto.
 */
class ErrorDetailDto
{
    /**
     * Error code.
     */
    public int $code = 0;

    /**
     * Error message.
     */
    public string $message = '';

    /**
     * Error file.
     */
    public string $file = '';

    /**
     * Error line.
     */
    public int $line = 0;

    /**
     * Error trace.
     */
    public string $trace = '';
}
