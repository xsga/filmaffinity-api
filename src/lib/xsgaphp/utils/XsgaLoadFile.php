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
namespace xsgaphp\utils;

/**
 * Import dependencies.
 */
use log4php\Logger;
use xsgaphp\utils\XsgaUtil;

/**
 * XsgaLoadFile class.
 */
class XsgaLoadFile
{

    
    /**
     * Load language property file.
     * 
     * @return array
     * 
     * @access public
     */
    public static function language() : array
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
        
    }//end language()


    /**
     * Load errors property file.
     * 
     * @return array
     * 
     * @access public
     */
    public static function errors() : array
    {
        // Get logger.
        $logger = Logger::getRootLogger();
        
        // Logger.
        $logger->debugInit();
        
        // Set file.
        $errorsFile = XsgaUtil::getPathTo('config').'errors.json';
        
        if (file_exists($errorsFile)) {
            
            // Load errors.
            $errors = json_decode(file_get_contents($errorsFile), true);
            
            // Logger.
            $logger->debug('Errors JSON file loads successfully');
            
        } else {

            $errors = array();
            
            // Logger.
            $logger->error('Error loading errors JSON file. File not found');
            
        }//end if
                
        // Logger.
        $logger->debugEnd();
        
        return $errors;
        
    }//end errors()


    /**
     * Load routes property file.
     * 
     * @return array
     * 
     * @access public
     */
    public static function routes() : array
    {
        // Get logger.
        $logger = Logger::getRootLogger();
        
        // Logger.
        $logger->debugInit();
        
        // Set file.
        $routesFile = XsgaUtil::getPathTo('config').'routes.json';
        
        if (file_exists($routesFile)) {
            
            // Load routes.
            $routes = json_decode(file_get_contents($routesFile), true);
            
            // Logger.
            $logger->debug('Routes JSON file loads successfully');
            
        } else {

            $routes = array();
            
            // Logger.
            $logger->error('Error loading routes JSON file. File not found');
            
        }//end if
                
        // Logger.
        $logger->debugEnd();
        
        return $routes;
        
    }//end routes()
    
    
}//end XsgaLoadFile class
