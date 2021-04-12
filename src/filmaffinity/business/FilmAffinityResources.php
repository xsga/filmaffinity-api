<?php
/**
 * FilmAffinityResources.
 *
 * Resources from FilmAffinity.
 *
 * PHP Version 7
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
    public function getResourceFile($type, $fileName, $mode = '')
    {
        // Logger.
        $this->logger->debugInit();
        
        switch ($type) {
            
            case 'json':
                
                // Logger.
                $this->logger->debug('Get JSON resource');
                
                // Set common variables.
                $resourceFileName = strtolower($fileName).ucfirst(strtolower($_ENV['LANGUAGE'])).'.json';
                $pathToResource   = DIRECTORY_SEPARATOR.'..';
                $pathToResource  .= DIRECTORY_SEPARATOR.'resources';
                $pathToResource  .= DIRECTORY_SEPARATOR.'json';
                $pathToResource  .= DIRECTORY_SEPARATOR;
                $resource         = realpath(dirname(__FILE__)).$pathToResource.$resourceFileName;
                $errorMsg         = 'JSON file not found ('.$resourceFileName.')';
                $errorNum         = 203;
                
                break;
                
            case 'schema':
                
                // Logger.
                $this->logger->debug('Get JSON schema resource');
                
                // Set common variables.
                $resourceFileName = strtolower($fileName).'.schema.json';
                $pathToResource   = DIRECTORY_SEPARATOR.'..';
                $pathToResource  .= DIRECTORY_SEPARATOR.'resources';
                $pathToResource  .= DIRECTORY_SEPARATOR.'schema';
                $pathToResource  .= DIRECTORY_SEPARATOR.$mode;
                $pathToResource  .= DIRECTORY_SEPARATOR;
                $resource         = realpath(dirname(__FILE__)).$pathToResource.$resourceFileName;
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
