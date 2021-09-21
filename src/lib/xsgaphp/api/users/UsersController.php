<?php
/**
 * UsersController.
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
use xsgaphp\api\abstract\XsgaAbstractApiController;
use xsgaphp\api\errors\ErrorsBusiness;

/**
 * UsersController.
 */
class UsersController extends XsgaAbstractApiController
{

    /**
     * User login.
     * 
     * @api
     * 
     * @param array $request Request.
     * @param array $filters Request filters.
     * @param array $body    Request body.
     * 
     * @return void
     * 
     * @access public
     */
    public function login(array $request, array $filters, array $body) : void
    {
        // Logger.
        $this->logger->debugInit();

        

        // Get response.
        $this->getResponse();

        // Logger.
        $this->logger->debugEnd();

        return;

    }//end login()


    /**
     * User register.
     * 
     * @api
     * 
     * @param array $request Request.
     * @param array $filters Request filters.
     * @param array $body    Request body.
     * 
     * @return void
     * 
     * @access public
     */
    public function register(array $request, array $filters, array $body) : void
    {
        // Logger.
        $this->logger->debugInit();

        

        // Get response.
        $this->getResponse();

        // Logger.
        $this->logger->debugEnd();

        return;

    }//end register()


}//end UsersController class
