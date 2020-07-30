<?php
/**
 * SearchApiController.
 *
 * This class manages all API petitions from MOVIE module.
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
 * Class MovieApiController.
 */
class MovieApiController extends XsgaAbstractApiController
{
    
    
    /**
     * Get movie GET method.
     * 
     * @param array $params Filmaffinity movie ID.
     * 
     * @return void
     * 
     * @access public
     */
    public function getGetMovie(array $params)
    {
        
        $this->getResponse($movieId);
        
    }//end getGetMovie()
    
    
    
}//end MovieApiController class
