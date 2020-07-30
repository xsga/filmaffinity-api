<?php
/**
 * XsgaAbstractClass.
 *
 * PHP version 7
 *
 * @author  xsga <xsegales@outlook.com>
 * @version 1.0.0
 */
 
/**
 * Namespace.
 */
namespace xsgaphp\mvc;

/**
 * Import namespaces.
 */
use log4php\Logger;

/**
 * XsgaAbstractClass class.
 *
 * This abstract class defines a generic class pattern.
 */
abstract class XsgaAbstractClass
{
    
    /**
     * Logger.
     * 
     * @var Logger
     * 
     * @access public
     */
    public $logger;
   
    
    /**
     * Constructor.
     * 
     * @access public
     */
    public function __construct()
    {
        
        // Set logger.
        $this->logger = Logger::getRootLogger();
        
    }//end __construct()
    

}//end XsgaAbstractClass class
