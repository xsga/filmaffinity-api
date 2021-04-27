<?php
/**
 * XsgaAbstractDao.
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
use Doctrine\ORM\EntityManager;
use xsgaphp\doctrine\XsgaDoctrineEM;
use xsgaphp\core\XsgaAbstractClass;

/**
 * XsgaAbstractDao class.
 */
abstract class XsgaAbstractDao extends XsgaAbstractClass
{
    
    /**
     * Doctrine entity manager.
     * 
     * @var EntityManager
     * 
     * @access protected
     */
    protected $em;
    
        
    /**
     * Constructor.
     * 
     * @access public
     */
    public function __construct()
    {
        // Executes parent constructor.
        parent::__construct();
        
        // Get entity manager.
        $this->em = XsgaDoctrineEM::getEntityManager();
        
    }//end __construct()
    
    
}//end XsgaAbstractDao class
