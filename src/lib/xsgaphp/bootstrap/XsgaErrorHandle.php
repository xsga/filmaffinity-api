<?php
/**
 * Error handle.
 *
 * PHP Version 8
 *
 * @author  xsga <parker@xsga.es>
 * @license MIT
 * @version 1.0.0
 */

// Set error reporting level.
error_reporting(E_ALL);


/**
 * Error handler.
 *
 * @param integer $errno   Error number.
 * @param string  $errstr  Error message.
 * @param string  $errfile Error file.
 * @param integer $errline Error line.
 *
 * @throws ErrorException Error exception.
 *
 * @return void
 *
 * @access public
*/
function exceptionErrorHandler(int $errno, string $errstr, string $errfile, int $errline) : void
{
    if ($errno !== E_USER_DEPRECATED) {
        // Error exception.
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }//end if

}//end exception_error_handler()


// Register exceptionErrorHandler.
$errorTypes = (E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
set_error_handler('exceptionErrorHandler', $errorTypes);
