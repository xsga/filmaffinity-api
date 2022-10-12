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
     *
     * @var integer
     *
     * @access public
     */
    public $code = -1;

    /**
     * HTTP code.
     *
     * @var integer
     *
     * @access public
     */
    public $httpCode = 500;

    /**
     * Error message.
     *
     * @var string
     *
     * @access public
     */
    public $message = '';
}
