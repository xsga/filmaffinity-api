<?php
/**
 * XsgaLoadFile.
 *
 * This file contains the XsgaLoadFile class.
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

/**
 * XsgaLoadFile class.
 */
class XsgaLoadFile
{

    
    /**
     * Loads JSON file.
     * 
     * @param string  $path     Location file path.
     * @param string  $fileName File name.
     * @param boolean $mode     Output mode: true (array) false (object)
     * 
     * @return array|object
     * 
     * @access public
     */
    public static function loadJson(string $path, string $fileName, bool $mode = true) : array|object
    {
        // Get logger.
        $logger = Logger::getRootLogger();
        
        // Logger.
        $logger->debugInit();
        
        // Set file location.
        $fileLocation = $path.$fileName;
        
        if (file_exists($fileLocation)) {
            
            // Load file.
            $content = json_decode(file_get_contents($fileLocation), $mode);
            
            // Logger.
            $logger->debug("Load \"$fileName\" file");
            
        } else {

            if ($mode === true) {
                $content = array();
            } else {
                $content = new \stdClass();
            }//end if
                        
            // Logger.
            $logger->error("File \"$fileName\" not found");
            
        }//end if
                
        // Logger.
        $logger->debugEnd();
        
        return $content;

    }//end loadJson()
    
    
}//end XsgaLoadFile class
