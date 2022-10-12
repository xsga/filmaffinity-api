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
     *
     * @var integer
     *
     * @access public
     */
    public $code = 0;

    /**
     * Error message.
     *
     * @var string
     *
     * @access public
     */
    public $message = '';

    /**
     * Error file.
     *
     * @var string
     *
     * @access public
     */
    public $file = '';

    /**
     * Error line.
     *
     * @var integer
     *
     * @access public
     */
    public $line = 0;

    /**
     * Error trace.
     *
     * @var string
     *
     * @access public
     */
    public $trace = '';
}
