<?php
/**
 * ResourcesApiController.
 *
 * This class manages all API petitions from RESOURCES module.
 *
 * PHP version 7
 *
 * @author  xsga <xsegales@outlook.com>
 * @license MIT
 * @version 1.0.0
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
use api\controller\helpers\ResourcesACHelper;

/**
 * Class ResourcesApiController.
 */
class ResourcesApiController extends XsgaAbstractApiController
{
    
    /**
     * Helper.
     * 
     * @var ResourcesACHelper
     * 
     * @access public
     */
    public $helper;
    
    /**
     * FilmAffinity resources.
     * 
     * @var FilmAffinityResources
     * 
     * @access public
     */
    public $resources;
    
    
    /**
     * Constructor.
     * 
     * @access public
     */
    public function __construct()
    {
        
        // Executes parent constructor.
        parent::__construct();
        
        // Set helper.
        $this->helper = new ResourcesACHelper();
        
        // Set FilmAffinity resources.
        $this->resources = new FilmAffinityResources();
        
    }//end __construct()
    
    
    /**
     * Get JSON file GET method.
     * 
     * @api
     * 
     * @param array $params JSON file to get.
     *
     * @return void
     *
     * @access public
     */
    public function getJson(array $params = array())
    {
        
        // Logger.
        $this->logger->debugInit();
        
        // Validates input parameters.
        $this->getJsonValidations($params);
        
        // Get response.
        $this->getResponse($this->resources->getJsonFile($params[0]), false);
        
        // Logger.
        $this->logger->debugEnd();
        
    }//end getJson()
    
    
    /**
     * Get JSON schema GET method.
     *
     * @api
     *
     * @param array $params JSON file to get.
     *
     * @return void
     *
     * @access public
     */
    public function getSchema(array $params = array())
    {
        
        // Logger.
        $this->logger->debugInit();
        
        // Validates input parameters.
        $this->getSchemaValidations($params);
        
        // Get response.
        $this->getResponse($this->resources->getSchemaFile($params[0], $params[1]), false);
        
        // Logger.
        $this->logger->debugEnd();
        
    }//end getSchema()
    
    
    /**
     * Validates input data for json API endpoint.
     * 
     * @param array $params Input parameters.
     * 
     * @return void
     * 
     * @access private
     */
    private function getJsonValidations(array $params)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        // Validates parameters.
        $this->valNumberOfParams($params, 1);
        $this->helper->valJsonFile($params[0]);
        
        // Logger.
        $this->logger->debugEnd();
        
    }//end getJsonValidations()
    
    
    /**
     * Validates input data for schema API endpoint.
     *
     * @param array $params Input parameters.
     *
     * @return void
     *
     * @access private
     */
    private function getSchemaValidations(array $params)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        // Validates parameters.
        $this->valNumberOfParams($params, 2);
        $this->helper->valSchemaMode($params[0]);
        $this->helper->valSchemaFile($params[1]);
        
        // Logger.
        $this->logger->debugEnd();
        
    }//end getSchemaValidations()
    
    
}//end ResourcesApiController class
