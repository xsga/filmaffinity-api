<?php
/**
 * ResourcesACHelper.
 *
 * This class provide helper methods to ResourcesApiController class.
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
namespace api\filmaffinity\controller\helpers;

/**
 * Import dependencies.
 */
use xsgaphp\core\XsgaAbstractClass;
use xsgaphp\exceptions\XsgaValidationException;

/**
 * Class ResourcesACHelper.
 */
class ResourcesACHelper extends XsgaAbstractClass
{
    
    /**
     * Valid JSON files.
     * 
     * @var array
     * 
     * @access public
     */
    public $jsonFiles = array('genres', 'countries');
    
    /**
     * Valid schema mode.
     *
     * @var array
     *
     * @access public
     */
    public $schemaModes = array('input', 'output');
    
    /**
     * Valid schema files.
     * 
     * @var array
     * 
     * @access public
     */
    public $schemaFiles = array(
            'adv_search', 
            'search', 
            'api_error_dev', 
            'api_error', 
            'genres', 
            'film', 
            'search_results'
    );
    
    
    /**
     * Validates if parameter value exists in array.
     * 
     * @param string $param Parameter to validate.
     * @param string $type  Validation type.
     * 
     * @throws XsgaValidationException When parameter is not valid.
     * 
     * @return void
     * 
     * @access public
     */
    public function valParamIsValid(string $param, string $type) : void
    {
        // Logger.
        $this->logger->debugInit();
        
        // Set array to search, error message and error number.
        switch ($type) {
            
            case 'json_name':
                
                // Logger.
                $this->logger->debug('JSON filename validation');
                
                // Set common variables.
                $searchArray = $this->jsonFiles;
                $errorMsg    = 'JSON file not valid';
                $errorNum    = 204;
                
                break;
                
            case 'schema_mode':
                
                // Logger.
                $this->logger->debug('JSON schema mode validation');
                
                // Set common variables.
                $searchArray = $this->schemaModes;
                $errorMsg    = 'JSON schema mode not valid (input or output)';
                $errorNum    = 206;
                
                break;
                
            case 'schema_name':
                
                // Logger.
                $this->logger->debug('JSON schema filename validation');
                
                // Set common variables.
                $searchArray = $this->schemaFiles;
                $errorMsg    = 'JSON schema file not valid';
                $errorNum    = 205;
                
                break;
            
            default:
                
                // Set common variables.
                $searchArray = array();
                $errorMsg    = 'Validation type not valid';
                $errorNum    = 101;
                
                // Logger.
                $this->logger->warn($errorMsg);
                    
        }//end switch
        
        // Search parameter in array.
        if (!in_array($param, $searchArray)) {
            
            // Logger.
            $this->logger->debugValidationKO();
            $this->logger->error($errorMsg);
            
            throw new XsgaValidationException($errorMsg, $errorNum);
            
        }//end if
        
        // Logger.
        $this->logger->debugValidationOK();
        $this->logger->debugEnd();
        
    }//end valParamIsValid()
    
    
}//end ResourcesACHelper class
