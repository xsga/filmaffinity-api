<?php
/**
 * XsgaLangFiles.
 *
 * This file contains the XsgaLangFiles class.
 * 
 * PHP version 7
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
 * Import namespaces.
 */
use log4php\Logger;

/**
 * XsgaLangFiles class.
 */
class XsgaLangFiles
{

    
    /**
     * Load language property file.
     * 
     * @param string $fileName File name.
     * @param string $folder   Folder.
     * 
     * @return array
     * 
     * @access public
     */
    public static function load($fileName, $folder=null)
    {
        
        // Get logger.
        $logger = Logger::getRootLogger();
        
        // Logger.
        $logger->debugInit();
        
        // Set language.
        $language = strtolower(FA_LANGUAGE);
        
        // Set path to file.
        if ($folder === null) {
            $file = $fileName.ucfirst($language).'.php';
        } else {
            $file = $folder.'/'.$fileName.ucfirst($language).'.php';
        }//end if
        
        // Path to language folder.
        $pathToLanguage  = DIRECTORY_SEPARATOR.'..';
        $pathToLanguage .= DIRECTORY_SEPARATOR.'..';
        $pathToLanguage .= DIRECTORY_SEPARATOR.'..';
        $pathToLanguage .= DIRECTORY_SEPARATOR.'api';
        $pathToLanguage .= DIRECTORY_SEPARATOR.'language';
        $pathToLanguage .= DIRECTORY_SEPARATOR;
        
        // Set language file.
        $langFile = realpath(dirname(__FILE__)).$pathToLanguage.$file;
        
        // Initialize language variable.
        $lang = array();
        
        if (file_exists($langFile)) {
            
            // Load language.
            require $langFile;
            
            // Logger.
            $logger->debug('Load "'.$language.'" property file: '.$file);
            
        } else {
            
            // Logger.
            $logger->error('Language property file not found ('.$file.')');
            
        }//end if
                
        // Logger.
        $logger->debugEnd();
        
        return $lang;
        
    }//end load()
    
    
}//end XsgaLangFiles class
