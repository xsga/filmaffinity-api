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
namespace Xsga\FilmAffinityApi\Dto;

/**
 * Class ErrorDto.
 */
class ErrorDto
{
    /**
     * Error code.
     */
    public int $code = 0;

    /**
     * Error message.
     */
    public string $message = '';
}
