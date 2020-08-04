<?php
/**
 * XsgaLangFiles.
 *
 * This file contains the XsgaLangFiles class.
 * 
 * PHP version 7
 *
 * @author  xsga <xsegales@outlook.com>
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
        
        // Initialize language variable.
        $lang = array();
        
        // Get logger.
        $logger = Logger::getRootLogger();
        
        // Logger.
        $logger->debugInit();
        
        // Set language.
        $language = FA_LANGUAGE;
        
        // Set path to file.
        if ($folder === null) {
            $file = $fileName.ucfirst($language).'.php';
        } else {
            $file = $folder.'/'.$fileName.ucfirst($language).'.php';
        }//end if
        
        // Set language file.
        $langFile = realpath(dirname(__FILE__)).'/../../../api/language/'.$file;
                
        if (file_exists($langFile) === true) {
            
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
