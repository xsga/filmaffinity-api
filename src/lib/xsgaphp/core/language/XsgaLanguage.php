<?php
/**
 * XsgaLanguage.
 *
 * This file contains the XsgaLanguage class.
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
namespace xsgaphp\core\language;

/**
 * Import dependencies.
 */
use log4php\Logger;
use xsgaphp\core\exceptions\XsgaSecurityException;
use xsgaphp\core\utils\XsgaLoadFile;
use xsgaphp\core\utils\XsgaPath;

/**
 * Class XsgaLanguage.
 */
class XsgaLanguage
{
    
    /**
     * Language.
     * 
     * @var string
     * 
     * @access private
     */
    private static $lang = '';

    /**
     * Language data.
     * 
     * @var array
     * 
     * @access private
     */
    private static $data = array();
    
    
    /**
     * Get language property.
     * 
     * @param string  $key     Language property key.
     * @param boolean $refresh Refresh language data.
     * 
     * @return string
     * 
     * @access public
     */
    public static function get(string $key, $refresh = false) : string
    {
        // Get logger.
        $logger = Logger::getRootLogger();

        // Logger.
        $logger->debugInit();

        // Loads language.
        if (empty(static::$lang) || static::$lang !== $_ENV['LANGUAGE']) {

            // Logger.
            $logger->debug('Setting language to "'.$_ENV['LANGUAGE'].'"');

            static::$lang = $_ENV['LANGUAGE'];
            $refresh = true;

        }//end if

        // Loads language data.
        if (empty(static::$data) || $refresh) {

            // Logger.
            $logger->debug('Loading language "'.$_ENV['LANGUAGE'].'" data');
            
            $path = XsgaPath::getPathTo(array('src', 'common', 'language'));
            $file = strtolower($_ENV['LANGUAGE']).'.json';
            
            // Gets and saves language data.
            static::$data = XsgaLoadFile::loadJson($path, $file);
            
        }//end if

        // Gets key array.
        $keyArray = explode('.', $key);

        $error = false;

        $current = static::$data;

        // Gets language property.
        foreach ($keyArray as $value) {

            if (isset($current[$value])) {

                $current = $current[$value];

            } else {
                
                $logger->error("Language key \"$value\" not found");
                $error = true;
                break;

            }//end if

        }//end foreach        
        
        if ($error || empty($current) || is_array($current)) {

            // Logger.
            $logger->error("Language key \"$key\" not found");

            $prop = '';

        } else {

            // Logger.
            $logger->debug("Language key \"$key\" found");

            $prop = $current;

        }//end if

        // Logger.
        $logger->debugEnd();

        return $prop;
        
    }//end get()
    
    
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
    
    
}//end XsgaLanguage class
