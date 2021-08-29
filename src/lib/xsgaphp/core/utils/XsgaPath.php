<?php
/**
 * XsgaPath.
 *
 * This file contains the XsgaPath class.
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
 * XsgaPath class.
 */
class XsgaPath
{

    
    /**
     * Get path to.
     * 
     * @param string|array $pathItems Items from root path.
     * 
     * @return string
     * 
     * @access public
     */
    public static function getPathTo(string|array $pathItems) : string
    {
        // Initialize path.
        $path = $_ENV['APP_ROOT'];
        
        // Add path items to path.
        if (is_array($pathItems)) {
            foreach ($pathItems as $item) {
                $path .= $item.DIRECTORY_SEPARATOR;
            }//end foreach
        } else {
            $path .= $pathItems.DIRECTORY_SEPARATOR;
        }//end if
        
        return $path;
        
    }//end getPathTo()
    
    
}//end XsgaPath class
