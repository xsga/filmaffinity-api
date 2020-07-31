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
     * @return void
     *
     * @access public
     */
    public function getGetGenres()
    {
        
        // Get response.
        $this->getResponse(FilmAffinityResources::$genres);
        
    }//end getGetGenres()
    
    
    /**
     * Get countries list GET method.
     *
     * @return void
     *
     * @access public
     */
    public function getGetCountries()
    {
        
        // Get response.
        $this->getResponse(FilmAffinityResources::$countries);
        
    }//end getGetCountries()
    
    
}//end ResourcesApiController class
