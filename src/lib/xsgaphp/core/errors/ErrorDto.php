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
namespace xsgaphp\core\errors;

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
    public $code;

    /**
     * HTTP code.
     * 
     * @var integer
     * 
     * @access public
     */
    public $httpCode;
    
    /**
     * Error message.
     * 
     * @var string
     * 
     * @access public
     */
    public $message;
    
    
}//end ErrorDto class
