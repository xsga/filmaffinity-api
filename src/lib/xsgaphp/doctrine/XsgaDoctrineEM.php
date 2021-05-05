<?php
/**
 * XsgaDoctrineEM.
 *
 * This file contains the XsgaEntityManager class.
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
namespace xsgaphp\doctrine;

/**
 * Import dependencies.
 */
use xsgaphp\exceptions\XsgaSecurityException;
use Doctrine\ORM\EntityManager;
use xsgaphp\bootstrap\XsgaBootstrap;

/**
 * Class XsgaDoctrineEM.
 */
class XsgaDoctrineEM
{
    
    /**
     * Doctrine EntityManager.
     * 
     * @var EntityManager
     * 
     * @access private
     */
    private static $entityManager;
    
    
    /**
     * Get doctrine Entity Manager instance.
     * 
     * @return EntityManager
     * 
     * @access public
     */
    public static function getEntityManager() : EntityManager
    {
        // Create EM.
        if (empty(static::$entityManager)) {
            
            // Get Doctrine ORM setup.
            $doctrineSetup = XsgaBootstrap::setupDoctrineORM();
            
            // Create EntityManager.
            static::$entityManager = EntityManager::create(
                $doctrineSetup['connection'], 
                $doctrineSetup['config']
            );
            
        }//end if
        
        // Reload EM.
        if (!static::$entityManager->isOpen()) {
            
            // Reload EM.
            static::$entityManager = static::$entityManager->create(
                static::$entityManager->getConnection(), 
                static::$entityManager->getConfiguration()
            );
            
        }//end if
        
        return static::$entityManager;
        
    }//end getEntityManager()
    
    
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
    
    
}//end XsgaDoctrineEM class
