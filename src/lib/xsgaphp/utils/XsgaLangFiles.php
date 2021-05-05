<?php
/**
 * XsgaLangFiles.
 *
 * This file contains the XsgaLangFiles class.
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
namespace xsgaphp\utils;

/**
 * Import dependencies.
 */
use log4php\Logger;
use xsgaphp\utils\XsgaUtil;

/**
 * XsgaLangFiles class.
 */
class XsgaLangFiles
{

    
    /**
     * Load language property file.
     * 
     * @return array
     * 
     * @access public
     */
    public static function load() : array
    {
        // Get logger.
        $logger = Logger::getRootLogger();
        
        // Logger.
        $logger->debugInit();
        
        // Set file.
        $file = strtolower($_ENV['LANGUAGE']).'.json';

        // Path to language folder.
        $pathToLanguage  = XsgaUtil::getPathTo(array('src', 'language'));
                
        // Set language file.
        $langFile = $pathToLanguage.$file;
        
        if (file_exists($langFile)) {
            
            // Load language.
            $lang = json_decode(file_get_contents($langFile), true);
            
            // Logger.
            $logger->debug('Load "'.$_ENV['LANGUAGE'].'" property file: '.$file);
            
        } else {

            $lang = array();
            
            // Logger.
            $logger->error('Language property file not found ('.$file.')');
            
        }//end if
                
        // Logger.
        $logger->debugEnd();
        
        return $lang;
        
    }//end load()
    
    
}//end XsgaLangFiles class
