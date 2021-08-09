<?php
/**
 * ApiErrorsListDto.
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
namespace xsgaphp\api\dto;

/**
 * ApiErrorsListDto.
 */
class ApiErrorsListDto
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
     * Error description.
     * 
     * @var string
     * 
     * @access public
     */
    public $description;

    /**
     * Response status code.
     * 
     * @var integer
     * 
     * @access public
     */
    public $statusCode;

}//end ApiErrorsListDto class
