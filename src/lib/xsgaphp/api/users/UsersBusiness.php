<?php
/**
 * UsersBusiness.
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
use xsgaphp\api\abstract\XsgaAbstractApiBusiness;
use xsgaphp\api\dto\ApiUserDto;
use xsgaphp\api\users\UsersRepository;
use api\common\persistence\entity\ApiUsers;

/**
 * UsersBusiness class.
 */
class UsersBusiness extends XsgaAbstractApiBusiness
{

    
    /**
     * Get user.
     * 
     * @param string $userEmail User email.
     * 
     * @return ApiUserDto
     * 
     * @access public
     */
    public function getUser(string $userEmail) : ApiUserDto
    {
        // Logger.
        $this->logger->debugInit();

        // Gets users repository instance.
        $usersRepository = new UsersRepository();

        // Gets user.
        $userEntity = $usersRepository->getUser($userEmail);

        // Gets ApiUserDto instance.
        $userDto = new ApiUserDto();

        if (empty($userEntity)) {

            // Logger.
            $this->logger->warn("User \"$userEmail\" not found");

        } else {

            // Maps entity to DTO.
            $userDto->setId($userEntity->getId());
            $userDto->setEmail($userEntity->getEmail());
            $userDto->setPassword($userEntity->getPassword());
            $userDto->setName($userEntity->getName());
            $userDto->setSurname1($userEntity->getSurname1());
            $userDto->setSurname2($userEntity->getSurname2());
            $userDto->setBirthdate($userEntity->getBirthdate());
            $userDto->setEmail2($userEntity->getEmail2());
            $userDto->setPhone1($userEntity->getPhone1());
            $userDto->setPhone2($userEntity->getPhone2());
            $userDto->setCreateDate($userEntity->getCreateDate());
            $userDto->setLastLogin($userEntity->getLastLogin());
            $userDto->setEnabled($userEntity->getEnabled());
            $userDto->setToken($userEntity->getToken());

        }//end if

        // Logger.
        $this->logger->debugEnd();

        return $userDto;

    }//end getUser()


    /**
     * Add user.
     * 
     * @param ApiUserDto $user User DTO.
     * 
     * @return integer
     * 
     * @access public
     */
    public function addUser(ApiUserDto $user) : int
    {
        // Logger.
        $this->logger->debugInit();

        // Gets users repository instance.
        $usersRepository = new UsersRepository();

        // Gets ApiUsers entity instance.
        $userEntity = new ApiUsers();

        // Maps DTO to entity.
        $userEntity->setEmail($user->getEmail());
        $userEntity->setPassword($user->getPassword());
        $userEntity->setName($user->getName());
        $userEntity->setSurname1($user->getSurname1());
        $userEntity->setSurname2($user->getSurname2());
        $userEntity->setBirthdate($user->getBirthdate());
        $userEntity->setEmail2($user->getEmail2());
        $userEntity->setPhone1($user->getPhone1());
        $userEntity->setPhone2($user->getPhone2());
        $userEntity->setCreateDate($user->getCreateDate());
        $userEntity->setLastLogin($user->getLastLogin());
        $userEntity->setEnabled($user->getEnabled());
        $userEntity->setToken($user->getToken());

        // Adds user.
        $userId = $usersRepository->addUser($userEntity);

        // Logger.
        $this->logger->info('User "'.$user->getEmail().'" added successfully');

        // Logger.
        $this->logger->debugEnd();

        return $userId;

    }//end addUser()


    /**
     * Update password.
     * 
     * @param ApiUserDto $user User DTO.
     * 
     * @return boolean
     * 
     * @access public
     */
    public function updatePassword(ApiUserDto $user) : bool
    {
        // Logger.
        $this->logger->debugInit();

        $out = false;

        // Gets users repository instance.
        $usersRepository = new UsersRepository();

        // Gets user data entity.
        $userEntity = $usersRepository->getUser($user->getEmail());

        if (empty($userEntity)) {

            // Logger.
            $this->logger->warn("User \"$userEmail\" not found");

        } else {

            // Maps DTO to entity.
            $userEntity->setEmail($user->getEmail());
            $userEntity->setPassword($user->getPassword());
            $userEntity->setName($user->getName());
            $userEntity->setSurname1($user->getSurname1());
            $userEntity->setSurname2($user->getSurname2());
            $userEntity->setBirthdate($user->getBirthdate());
            $userEntity->setEmail2($user->getEmail2());
            $userEntity->setPhone1($user->getPhone1());
            $userEntity->setPhone2($user->getPhone2());
            
        }//end if

        

        // Adds user.
        $userId = $usersRepository->addUser($userEntity);

        // Logger.
        $this->logger->info('User "'.$user->getEmail().'" added successfully');

        // Logger.
        $this->logger->debugEnd();

        return $userId;

    }//end updatePassword()


}//end UsersBusiness class
