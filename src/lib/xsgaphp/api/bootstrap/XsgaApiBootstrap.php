<?php
/**
 * XsgaApiBootstrap.
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
namespace xsgaphp\api\bootstrap;

/**
 * Import dependencies.
 */
use Dotenv\Dotenv;

/**
 * Bootstrap class.
 */
class XsgaApiBootstrap
{


    /**
     * Validates custom properties.
     * 
     * @param Dotenv $dotenv DotEnv object.
     * 
     * @return void
     * 
     * @access public
     */
    public static function valProps(Dotenv $dotenv) : void
    {
        $dotenv->required('SECURITY_TYPE')->allowedValues(['0', '1']);
        $dotenv->required('URL_PATH');
        $dotenv->required('VENDOR')->notEmpty();
        
    }//end valProps()
    

}//end XsgaApiBootstrap class
