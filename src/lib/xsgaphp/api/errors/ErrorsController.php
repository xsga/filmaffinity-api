<?php
/**
 * ErrorsController.
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
namespace xsgaphp\api\errors;

/**
 * Import dependencies.
 */
use xsgaphp\api\abstract\XsgaAbstractApiController;
use xsgaphp\api\errors\ErrorsBusiness;

/**
 * ErrorsController.
 */
class ErrorsController extends XsgaAbstractApiController
{

    /**
     * Get API errors.
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
    public function getErrors(array $request, array $filters, array $body) : void
    {
        // Logger.
        $this->logger->debugInit();

        // Get error business instance.
        $errorBusiness = new ErrorsBusiness();

        // Get response.
        $this->getResponse($errorBusiness->getErrors());

        // Logger.
        $this->logger->debugEnd();

        return;

    }//end getErrors()


}//end ErrorsController class
