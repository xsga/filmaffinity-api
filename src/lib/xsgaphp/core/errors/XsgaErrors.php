<?php
/**
 * XsgaErrors.
 *
 * This file contains the XsgaErrors class.
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
 * Import dependencies.
 */
use log4php\Logger;
use xsgaphp\core\errors\ErrorDto;
use xsgaphp\core\language\XsgaLanguage;
use xsgaphp\core\utils\XsgaCheckFile;

/**
 * Class XsgaErrors.
 */
class XsgaErrors
{
    
    /**
     * Errors data.
     * 
     * @var array
     * 
     * @access private
     */
    private static $errors = array();

    /**
     * Error load status.
     * 
     * @var int
     * 
     * @access private
     */
    private static $status = 0;
    
    
    /**
     * Get error.
     * 
     * @param int $code Error code.
     * 
     * @return ErrorDto
     * 
     * @access public
     */
    public static function getError(int $code) : ErrorDto
    {
        // Get logger.
        $logger = Logger::getRootLogger();

        // Logger.
        $logger->debugInit();

        // Loads errors data.
        static::load();

        // Get error DTO instance.
        $errorDto = new ErrorDto();

        if (empty(static::$errors)) {

            // Set errors file loading error.
            $errorDto->code     = static::$status;
            $errorDto->httpCode = 500;
            $errorDto->message  = XsgaLanguage::get('errors.error_'.static::$status);

        } else {

            foreach(static::$errors as $key => $error) {

                if ($error['code'] === $code) {
                        
                    $errorDto->code     = $error['code'];
                    $errorDto->httpCode = $error['http'];
                    $errorDto->message  = XsgaLanguage::get("errors.error_$code");
    
                    break;
    
                }//end if
    
            }//end foreach

        }//end if

        // Logger.
        $logger->debugEnd();

        return $errorDto;
        
    }//end getError()


    /**
     * Get all errors.
     * 
     * @return ErrorDto[]
     * 
     * @access public
     */
    public static function getAllErrors() : array
    {
        // Get logger.
        $logger = Logger::getRootLogger();

        // Logger.
        $logger->debugInit();

        // Loads errors data.
        static::load();

        $list = array();

        foreach (static::$errors as $error) {

            if ($error['type'] === 'core' || $error['type'] === 'api' || $error['type'] === 'user') {

                $dto = new ErrorDto();

                $dto->code     = $error['code'];
                $dto->httpCode = $error['http'];
                $dto->message  = XsgaLanguage::get('errors.error_'.$error['code']);

                $list[] = $dto;

            }//end if

        }//end foreach

        // Logger.
        $logger->debugEnd();

        return $list;

    }//end getAllErrors()


    /**
     * Loads errors file.
     * 
     * @return void
     * 
     * @access private
     */
    private static function load() : void
    {
        // Get logger.
        $logger = Logger::getRootLogger();

        // Logger.
        $logger->debugInit();

        // Loads errors data.
        if (empty(static::$errors)) {

            // Logger.
            $logger->debug('Loading errors data');

            $errorsContent = array();

            // Load errors file.
            if (XsgaCheckFile::errors($errorsContent)) {

                // Gets and saves language data.
                static::$errors = $errorsContent;

            } else {

                if (empty($errorsContent)) {
                    static::$status = 1015;
                } else {
                    static::$status = 1016;
                }//end if
    
            }//end if

        }//end if

        // Logger.
        $logger->debugEnd();

    }//end load()
    
    
    /**
     * Clone.
     * 
     * @return void
     * 
     * @throws XsgaSecurityException
     * 
     * @access public
     */
    public function __clone() : void
    {
        throw new XsgaSecurityException('Operation not allowed');
        
    }//end __clone()
    
    
}//end XsgaErrors class
