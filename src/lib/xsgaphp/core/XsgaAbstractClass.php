<?php
/**
 * XsgaAbstractClass.
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
namespace xsgaphp\core;

/**
 * Import dependencies.
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
     * @access protected
     */
    protected $logger;
   
    
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
