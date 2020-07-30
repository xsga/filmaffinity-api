<?php
/**
 * SearchApiController.
 *
 * This class manages all API petitions from SEARCH module.
 *
 * PHP version 7
 *
 * @author  xsga <xsegales@outlook.com>
 * @version 1.0.0
 *
 */

/**
 * Namespace.
 */
namespace api\controller;

/**
 * Import namespaces.
 */
use xsgaphp\api\controller\XsgaAbstractApiController;

/**
 * Class SearchApiController.
 */
class SearchApiController extends XsgaAbstractApiController
{
    
    
    /**
     * Do search POST method.
     * 
     * @return void
     * 
     * @access public
     */
    public function postDoSearch()
    {
        
        $data = json_decode(file_get_contents('php://input'), false);
        
        $this->getResponse($data);
        
    }//end postDoSearch()
    
    
    /**
     * Do advanced search POST method.
     * 
     * @return void
     * 
     * @access public
     */
    public function postDoAdvSearch()
    {
        
        $data = json_decode(file_get_contents('php://input'), false);
        
        $this->getResponse($data);
        
    }//end postDoAdvSearch()
    
    
}//end SearchApiController class
