<?php
/**
 * ApiErrorDevDto.
 * 
 * PHP Version 7
 * 
 * @author  xsga <parker@xsga.es>
 * @license MIT
 * @version 1.0.0
 */

/**
 * Namespace.
 */
namespace api\model\dto;

/**
 * Class ApiErrorDevDto.
 */
class ApiErrorDevDto
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
     * Error message.
     * 
     * @var string
     * 
     * @access public
     */
    public $message;
    
    /**
     * Error file.
     * 
     * @var string
     * 
     * @access public
     */
    public $file;
    
    /**
     * Error line.
     * 
     * @var integer
     * 
     * @access public
     */
    public $line;
    
    /**
     * Error trace.
     * 
     * @var string
     * 
     * @access public
     */
    public $trace;
    
    
}//end ApiErrorDevDto class
