<?php
/**
 * XsgaAbstractApiController.
 *
 * PHP version 7
 *
 * @author  xsga <xsegales@outlook.com>
 * @version 1.0.0
 */
 
/**
 * Namespace.
 */
namespace xsgaphp\api\controller;

/**
 * Import namespaces.
 */
use xsgaphp\mvc\XsgaAbstractClass;
use xsgaphp\api\XsgaAPIRouter;

/**
 * XsgaAbstractApiController class.
 *
 * This abstract class defines the controller's pattern.
 */
abstract class XsgaAbstractApiController extends XsgaAbstractClass
{
    
    
    /**
     * Get response.
     * 
     * @param mixed $data response data.
     * 
     * @return void
     * 
     * @access public
     */
    public function getResponse($data)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        ob_clean();
        XsgaAPIRouter::getHeaders();
        echo json_encode($data);
        
        // Logger.
        $this->logger->debugEnd();
        
    }//end getResponse()
    
    
}//end XsgaAbstractController class
