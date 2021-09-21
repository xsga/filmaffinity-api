<?php
/**
 * XsgaApiSecurity.
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
namespace xsgaphp\api\security;

/**
 * Import dependencies.
 */
use xsgaphp\core\abstract\XsgaAbstractClass;
use xsgaphp\core\exceptions\XsgaValidationException;
use xsgaphp\core\exceptions\XsgaSecurityException;
use xsgaphp\core\exceptions\XsgaObjectNotFoundException;
use xsgaphp\core\utils\XsgaPassword;
use xsgaphp\api\users\UsersBusiness;

/**
 * XsgaApiSecurity.
 */
class XsgaApiSecurity extends XsgaAbstractClass
{

    
    /**
     * Basic HTTP security.
     * 
     * @return void
     * 
     * @throws XsgaValidationException
     * 
     * @access public
     */
    public function basic() : void
    {
        // Logger.
        $this->logger->debugInit();

        // Validates user name.
        if (isset($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_USER'])) {

            // Set user name.
            $user = $_SERVER['PHP_AUTH_USER'];

        } else {

            // Error message.
            $errorMsg = 'User name not provided';

            // Logger.
            $this->logger->error($errorMsg);

            throw new XsgaValidationException($errorMsg, 1011);

        }//end if

        // Validates user password.
        if (isset($_SERVER['PHP_AUTH_PW']) && !empty($_SERVER['PHP_AUTH_PW'])) {

            // Set password.
            $password = $_SERVER['PHP_AUTH_PW'];

        } else {
            
            // Error message.
            $errorMsg = 'User password not provided';

            // Logger.
            $this->logger->error($errorMsg);

            throw new XsgaValidationException($errorMsg, 1012);

        }//end if
        
        // Gets user data.
        $userBusiness = new UsersBusiness();
        $userData     = $userBusiness->getUser($user);

        if (empty($userData->getId())) {

            // Error message.
            $errorMsg = 'User not found';

            // Logger.
            $this->logger->error($errorMsg);

            throw new XsgaObjectNotFoundException($errorMsg, 1014);

        }//end if

        // Validates password.
        if (XsgaPassword::verify($password, $userData->getPassword())) {

            // Logger.
            $this->logger->debug('User login successfully');

            if (XsgaPassword::needsRehash($userData->getPassword())) {

                // Logger.
                $this->logger->debug('Re-hashing user password');

                // Re-hash user password.
                $newHash = XsgaPassword::getHash($password);

                // TODO: save new user password hash.

            }//end if

        } else {

            // Error message.
            $errorMsg = 'Incorrect user name or password';

            // Logger.
            $this->logger->error($errorMsg);

            throw new XsgaSecurityException($errorMsg, 1013);

        }//end if

        // Logger.
        $this->logger->debugEnd();

    }//end basic()


}//end XsgaApiSecurity class
