<?php

/**
 * ResponseDto.
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
 * ResponseDto class.
 */
class ResponseDto
{
    /**
     * Status.
     */
    public string $status = '';

    /**
     * Status code.
     */
    public int $statusCode = -1;

    /**
     * Response object.
     */
    public mixed $response = '';
}
