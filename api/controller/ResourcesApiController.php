<?php
/**
 * ResourcesApiController.
 *
 * This class manages all API petitions from RESOURCES module.
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
use api\business\FilmAffinityResources;

/**
 * Class ResourcesApiController.
 */
class ResourcesApiController extends XsgaAbstractApiController
{
    
    
    /**
     * Get genres list GET method.
     * 
     * @api
     *
     * @return void
     *
     * @access public
     */
    public function getGetGenres()
    {
        
        // Logger.
        $this->logger->debugInit();
        
        // Get response.
        $this->getResponse(FilmAffinityResources::$genres);
        
        // Logger.
        $this->logger->debugEnd();
        
    }//end getGetGenres()
    
    
    /**
     * Get countries list GET method.
     * 
     * @api
     *
     * @return void
     *
     * @access public
     */
    public function getGetCountries()
    {
        
        // Logger.
        $this->logger->debugInit();
        
        // Get response.
        $this->getResponse(FilmAffinityResources::$countries);
        
        // Logger.
        $this->logger->debugEnd();
        
    }//end getGetCountries()
    
    
}//end ResourcesApiController class
