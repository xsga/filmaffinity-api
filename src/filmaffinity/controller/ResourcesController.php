<?php
/**
 * ResourcesController.
 *
 * This class manages all API petitions from RESOURCES module.
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
namespace api\filmaffinity\controller;

/**
 * Import dependencies.
 */
use xsgaphp\api\controller\XsgaAbstractApiController;
use api\filmaffinity\business\FilmAffinityResources;
use api\filmaffinity\controller\helpers\ResourcesACHelper;

/**
 * Class ResourcesController.
 */
class ResourcesController extends XsgaAbstractApiController
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
     * @param array $request Request.
     * @param array $filters Request filters.
     * @param array $body    Request body.
     *
     * @return void
     *
     * @access public
     */
    public function getJson(array $request, array $filters, array $body) : void
    {
        // Logger.
        $this->logger->debugInit();

        // TODO: tratar parámetros.
        unset($request[0]);
        $request = array_values($request);
        
        // Validates input parameters.
        $this->getJsonValidations($request);
        
        // Get response.
        $this->getResponse($this->resources->getResourceFile('json', $request[0]), false);
        
        // Logger.
        $this->logger->debugEnd();
        
    }//end getJson()
    
    
    /**
     * Get JSON schema GET method.
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
    public function getSchema(array $request, array $filters, array $body) : void
    {
        // Logger.
        $this->logger->debugInit();

        // TODO: tratar parámetros.
        unset($request[0]);
        unset($request[1]);
        $request = array_values($request);
        
        // Validates input parameters.
        $this->getSchemaValidations($request);
        
        // Get response.
        $this->getResponse($this->resources->getResourceFile('schema', $request[1], $request[0]), false);
        
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
    private function getJsonValidations(array $params) : void
    {
        // Logger.
        $this->logger->debugInit();
        
        // Validates number of parameters: 1 parameter expected (JSON file name).
        $this->valNumberOfParams($params, 1);
        
        // Validates that JSON file name is valid.
        $this->helper->valParamIsValid($params[0], 'json_name');
        
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
    private function getSchemaValidations(array $params) : void
    {
        // Logger.
        $this->logger->debugInit();
        
        // Validates number of parameters: 2 parameters expected (mode and schema name).
        $this->valNumberOfParams($params, 2);
        
        // Validates that mode is valid: input or output.
        $this->helper->valParamIsValid($params[0], 'schema_mode');
        
        // Validates that schema name is valid.
        $this->helper->valParamIsValid($params[1], 'schema_name');
        
        // Logger.
        $this->logger->debugEnd();
        
    }//end getSchemaValidations()
    
    
}//end ResourcesController class
