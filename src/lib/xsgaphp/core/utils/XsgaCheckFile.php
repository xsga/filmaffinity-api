<?php
/**
 * XsgaCheckFile.
 *
 * This file contains the XsgaCheckFile class.
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
namespace xsgaphp\core\utils;

/**
 * Import dependencies.
 */
use log4php\Logger;
use xsgaphp\core\utils\XsgaPath;
use xsgaphp\core\utils\XsgaLoadFile;

/**
 * XsgaCheckFile class.
 */
class XsgaCheckFile
{

    
    /**
     * Checks console actions JSON file.
     * 
     * @param array $result File content or errors.
     * 
     * @return boolean
     * 
     * @access public
     */
    public static function consoleActions(array &$result = null) : bool
    {
        // Get logger.
        $logger = Logger::getRootLogger();
        
        // Logger.
        $logger->debugInit();
        
        // Initialize result.
        $result = array();

        // Fields.
        $fields = array('id', 'name', 'desc', 'class', 'method');

        // Validates file.
        $out = static::validateFile(XsgaPath::getPathTo('config'), 'console-actions.json', $fields, $result);

        // Logger.
        $logger->debugEnd();
        
        return $out;

    }//end consoleActions()


    /**
     * Checks api routes JSON file.
     * 
     * @param array $result File content or errors.
     * 
     * @return boolean
     * 
     * @access public
     */
    public static function apiRoutes(array &$result = null) : bool
    {
        // Get logger.
        $logger = Logger::getRootLogger();
        
        // Logger.
        $logger->debugInit();
        
        // Initialize result.
        $result = array();

        // Fields.
        $fields = array('name', 'http_method', 'pattern', 'controller', 'method');

        // Validates file.
        $out = static::validateFile(XsgaPath::getPathTo('config'), 'api-routes.json', $fields, $result);

        // Logger.
        $logger->debugEnd();
        
        return $out;

    }//end apiRoutes()


    /**
     * Checks errors JSON file.
     * 
     * @param array $result File content or errors.
     * 
     * @return boolean
     * 
     * @access public
     */
    public static function errors(array &$result = null) : bool
    {
        // Get logger.
        $logger = Logger::getRootLogger();
        
        // Logger.
        $logger->debugInit();
        
        // Initialize result.
        $result = array();

        // Fields.
        $fields = array('code', 'http', 'type');

        // Validates file.
        $out = static::validateFile(XsgaPath::getPathTo('config'), 'errors.json', $fields, $result);

        // Logger.
        $logger->debugEnd();
        
        return $out;

    }//end errors()


    /**
     * Load and validates JSON file.
     * 
     * @param string $path   Path to file.
     * @param string $file   File name.
     * @param array  $fields JSON mandatory fields.
     * @param array  $result Validation errors array or file content.
     * 
     * @return boolean
     * 
     * @access private
     */
    private static function validateFile(string $path, string $file, array $fields, array &$result) : bool
    {
        // Get logger.
        $logger = Logger::getRootLogger();
        
        // Logger.
        $logger->debugInit();

        // Get file content.
        $content = XsgaLoadFile::loadJson($path, $file);

        if (empty($content)) {
            
            // Logger.
            $logger->error('Error loading file "'.$file.'"');
            $logger->debugValidationKO();

            return false;

        }//end if

        foreach ($content as $key => $value) {

            foreach ($fields as $field) {

                if (isset($value[$field])) {
                    continue;
                }//end if

                // Error message.
                $errorMsg = 'Element '.$key.': '.strtoupper($field).' key mandatory';

                // Logger.
                $logger->error($errorMsg);

                // Set error to output.
                $result[] = $errorMsg;

            }//end foreach

        }//end foreach

        if (empty($result)) {

            // Logger.
            $logger->debug('File loaded and checked successfully');
            $logger->debugValidationOK();

            $result = $content;
            $out    = true;

        } else {
            
            // Logger.
            $logger->error("File \"$file\" not valid");
            $logger->debugValidationKO();

            $out = false;

        }//end if
        
        // Logger.
        $logger->debugEnd();
        
        return $out;

    }//end validateFile()
    
    
}//end XsgaCheckFile class
