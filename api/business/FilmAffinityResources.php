<?php
/**
 * FilmAffinityResources.
 *
 * Resources from FilmAffinity.
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
namespace api\business;

/**
 * Import namespaces.
 */
use xsgaphp\mvc\XsgaAbstractClass;
use xsgaphp\exceptions\XsgaFileNotFoundException;

/**
 * Class FilmAffinityResources.
 */
class FilmAffinityResources extends XsgaAbstractClass
{
    
    
    /**
     * Get JSON file.
     * 
     * @param string $fileName JSON filename.
     * 
     * @throws XsgaFileNotFoundException When file not found.
     * 
     * @return string
     * 
     * @access public
     */
    public function getJsonFile($fileName)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        // Get json filename.
        $jsonFileName = strtolower($fileName).ucfirst(strtolower(FA_LANGUAGE)).'.json';
        
        // Json path and filename.
        $jsonFile = realpath(dirname(__FILE__)).'/../resources/json/'.$jsonFileName;
        
        if (file_exists($jsonFile)) {
            
            // Get file content.
            $jsonContent = file_get_contents($jsonFile);
            
        } else {
            
            // Error message.
            $errorMsg = 'JSON file not found ('.$jsonFileName.')';
            
            // Logger.
            $this->logger->error($errorMsg);
            
            throw new XsgaFileNotFoundException($errorMsg, 113);
            
        }//end if
        
        // Logger.
        $this->logger->debugEnd();
        
        return $jsonContent;
        
    }//end getJsonFile()
    
    
    /**
     * Get JSON schema file.
     *
     * @param string $mode     Mode: IN or OUT.
     * @param string $fileName JSON schema filename.
     *
     * @throws XsgaFileNotFoundException When file not found.
     *
     * @return string
     *
     * @access public
     */
    public function getSchemaFile($mode, $fileName)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        // Get json schema filename.
        $schemaFileName = strtolower($fileName).'.schema.json';
        
        // Json schema path and filename.
        $schemaFile = realpath(dirname(__FILE__)).'/../resources/schema/'.$mode.'/'.$schemaFileName;
        
        if (file_exists($schemaFile)) {
            
            // Get file content.
            $schemaContent = file_get_contents($schemaFile);
            
        } else {
            
            // Error message.
            $errorMsg = 'JSON file not found ('.$schemaFileName.')';
            
            // Logger.
            $this->logger->error($errorMsg);
            
            throw new XsgaFileNotFoundException($errorMsg, 115);
            
        }//end if
        
        // Logger.
        $this->logger->debugEnd();
        
        return $schemaContent;
        
    }//end getSchemaFile()
    
    
}//end FilmAffinityResources class
