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
     *
     * @var string
     *
     * @access public
     */
    public $status = '';

    /**
     * Status code.
     *
     * @var integer
     *
     * @access public
     */
    public $statusCode = -1;

    /**
     * Response object.
     *
     * @var mixed
     *
     * @access public
     */
    public $response = '';
}
