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
namespace xsgaphp\core\abstract;

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
        switch ($_ENV['APP_TYPE']) {

            case 'api':
                $this->logger = Logger::getRootLogger();
                break;

            case 'batch':
                $this->logger = Logger::getLogger('main');
                break;

            default:
                $this->logger = Logger::getRootLogger();

        }//end switch
        
    }//end __construct()
    

}//end XsgaAbstractClass class
