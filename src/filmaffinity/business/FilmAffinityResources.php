<?php
/**
 * FilmAffinityResources.
 *
 * Resources from FilmAffinity.
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
namespace api\filmaffinity\business;

/**
 * Import dependencies.
 */
use xsgaphp\core\XsgaAbstractClass;
use xsgaphp\exceptions\XsgaFileNotFoundException;
use xsgaphp\utils\XsgaUtil;

/**
 * Class FilmAffinityResources.
 */
class FilmAffinityResources extends XsgaAbstractClass
{
    
    
    /**
     * Get a resource file content.
     * 
     * @param string $type     Resource type.
     * @param string $fileName Resource filename.
     * @param string $mode     Resource mode.
     * 
     * @throws XsgaFileNotFoundException When resource file not found.
     * 
     * @return string
     * 
     * @access public
     */
    public function getResourceFile(string $type, string $fileName, string $mode = '') : string
    {
        // Logger.
        $this->logger->debugInit();
        
        switch ($type) {
            
            case 'json':
                
                // Logger.
                $this->logger->debug('Get JSON resource');
                
                // Set common variables.
                $resourceFileName = strtolower($fileName).ucfirst(strtolower($_ENV['LANGUAGE'])).'.json';
                $pathToResource   = XsgaUtil::getPathTo(array('src', 'filmaffinity', 'resources', 'json'));
                $resource         = $pathToResource.$resourceFileName;
                $errorMsg         = 'JSON file not found ('.$resourceFileName.')';
                $errorNum         = 203;
                
                break;
                
            case 'schema':
                
                // Logger.
                $this->logger->debug('Get JSON schema resource');
                
                // Set common variables.
                $resourceFileName = strtolower($fileName).'.schema.json';
                $pathToResource   = XsgaUtil::getPathTo(array('src', 'filmaffinity', 'resources', 'schema', $mode));
                $resource         = $pathToResource.$resourceFileName;
                $errorMsg         = 'JSON schema file not found ('.$resourceFileName.')';
                $errorNum         = 205;
                
                break;
                
            default:
                
                // Set common variables.
                $resourceFileName = '';
                $pathToResource   = '';
                $resource         = '';
                $errorMsg         = 'Resource type not valid';
                $errorNum         = 101;
                
                // Logger.
                $this->logger->warn($errorMsg);
            
        }//end switch
        
        if (file_exists($resource)) {

            // Get file content.
            $resourceContent = file_get_contents($resource);
            
        } else {
            
            // Logger.
            $this->logger->error($errorMsg);
            
            throw new XsgaFileNotFoundException($errorMsg, $errorNum);
            
        }//end if
        
        // Logger.
        $this->logger->debugEnd();
        
        return $resourceContent;
        
    }//end getResourceFile()
    
    
}//end FilmAffinityResources class
