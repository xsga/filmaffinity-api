<?php
/**
 * ResourcesACHelper.
 *
 * This class provide helper methods to ResourcesApiController class.
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
namespace api\controller\helpers;

/**
 * Import namespaces.
 */
use xsgaphp\mvc\XsgaAbstractClass;
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
    public $jsonFiles = array(
            'genres', 
            'countries'
    ); 
    
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
     * Valid schema mode.
     *
     * @var array
     *
     * @access public
     */
    public $schemaModes = array(
            'input',
            'output'
    );
    
    
    /**
     * Validates JSON file.
     * 
     * @param string $file JSON file.
     * 
     * @return void
     * 
     * @access public
     */
    public function valJsonFile($file)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        if (!in_array($file, $this->jsonFiles)) {
            
            // Error message.
            $errorMsg = 'JSON file not valid';
            
            // Logger.
            $this->logger->debugValidationKO();
            $this->logger->error($errorMsg);
            
            throw new XsgaValidationException($errorMsg, 114);
            
        }//end if
        
        // Logger.
        $this->logger->debugValidationOK();
        $this->logger->debugEnd();
        
    }//end valJsonFile()
    
    
    /**
     * Validates JSON schema file.
     *
     * @param string $file JSON schema file.
     *
     * @return void
     *
     * @access public
     */
    public function valSchemaFile($file)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        if (!in_array($file, $this->schemaFiles)) {
            
            // Error message.
            $errorMsg = 'JSON schema file not valid';
            
            // Logger.
            $this->logger->debugValidationKO();
            $this->logger->error($errorMsg);
            
            throw new XsgaValidationException($errorMsg, 115);
            
        }//end if
        
        // Logger.
        $this->logger->debugValidationOK();
        $this->logger->debugEnd();
        
    }//end valSchemaFile()
    
    
    /**
     * Validates JSON schema mode: INPUT or OUTPUT.
     *
     * @param string $file JSON schema mode.
     *
     * @return void
     *
     * @access public
     */
    public function valSchemaMode($mode)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        if (!in_array($mode, $this->schemaModes)) {
            
            // Error message.
            $errorMsg = 'JSON schema mode not valid (input or output)';
            
            // Logger.
            $this->logger->debugValidationKO();
            $this->logger->error($errorMsg);
            
            throw new XsgaValidationException($errorMsg, 116);
            
        }//end if
        
        // Logger.
        $this->logger->debugValidationOK();
        $this->logger->debugEnd();
        
    }//end valSchemaMode()
    
    
}//end ResourcesACHelper class
