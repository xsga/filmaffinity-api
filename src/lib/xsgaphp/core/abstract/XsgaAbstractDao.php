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
namespace xsgaphp\core\abstract;

/**
 * Import dependencies.
 */
use Doctrine\ORM\EntityManager;
use xsgaphp\core\doctrine\XsgaDoctrineEM;
use xsgaphp\core\abstract\XsgaAbstractClass;

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
