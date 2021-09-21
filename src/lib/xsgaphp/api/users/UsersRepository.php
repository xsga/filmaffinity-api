<?php
/**
 * UsersRepository.
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
namespace xsgaphp\api\users;

/**
 * Import dependencies.
 */
use xsgaphp\api\abstract\XsgaAbstractApiDao;
use api\common\persistence\entity\ApiUsers;

/**
 * UsersRepository.
 */
class UsersRepository extends XsgaAbstractApiDao
{

    
    /**
     * Get user.
     * 
     * @param string $userEmail User email.
     * 
     * @return ApiUsers|null
     * 
     * @access public
     */
    public function getUser(string $userEmail) : ApiUsers|null
    {
        // Logger.
        $this->logger->debugInit();

        // Set criteria.
        $criteria = array('email' => $userEmail);

        // Get results.
        $userEntity = $this->em->getRepository(ApiUsers::class)->findOneBy($criteria);

        // Logger.
        $this->logger->debugEnd();

        return $userEntity;

    }//end getUser()


    /**
     * Add user.
     * 
     * @param ApiUsers $user User entity.
     * 
     * @return integer
     * 
     * @access public
     */
    public function addUser(ApiUsers $user) : int
    {
        // Logger.
        $this->logger->debugInit();

        // Persists and commit changes.
        $this->em->persist($user);
        $this->em->flush();

        // Get ID.
        $userId = $user->getId();

        // Logger.
        $this->logger->debugEnd();

        return $userId;

    }//end addUser()


}//end UsersRepository class
