<?php
/**
 * SearchACHelper.
 *
 * This class provide helper methods to SearchApiController class..
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
namespace api\controller\helpers;

/**
 * Import namespaces.
 */
use xsgaphp\mvc\XsgaAbstractClass;
use xsgaphp\exceptions\XsgaValidationException;
use api\business\FilmAffinityResources;

/**
 * Class SearchACHelper.
 */
class SearchACHelper extends XsgaAbstractClass
{
    
    
    /**
     * Validates genre parameter.
     * 
     * @param string $genreParam
     * 
     * @return void
     * 
     * @access public
     */
    public function valGenre($genreParam)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        if (!array_key_exists($genreParam, FilmAffinityResources::$genres) && $genreParam <> '') {
            
            // Error message.
            $errorMsg = 'Genre code "'.$genreParam.'" it\'s not valid';
            
            // Logger.
            $this->logger->debugValidationKO();
            $this->logger->error($errorMsg);
            
            throw new XsgaValidationException($errorMsg);
            
        }//end if
        
        // Logger.
        $this->logger->debugValidationOK();
        $this->logger->debugEnd();
        
    }//end valGenre()
    
    
    /**
     * Validates country parameter.
     *
     * @param string $countryParam
     *
     * @return void
     *
     * @access public
     */
    public function valCountry($countryParam)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        if (!array_key_exists($countryParam, FilmAffinityResources::$countries) && $countryParam<> '') {
            
            // Error message.
            $errorMsg = 'Country code "'.$countryParam.'" it\'s not valid';
            
            // Logger.
            $this->logger->debugValidationKO();
            $this->logger->error($errorMsg);
            
            throw new XsgaValidationException($errorMsg);
            
        }//end if
        
        // Logger.
        $this->logger->debugValidationOK();
        $this->logger->debugEnd();
        
    }//end valCountry()
    
    
}//end SearchACHelper class
