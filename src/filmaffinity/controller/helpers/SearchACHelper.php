<?php
/**
 * SearchACHelper.
 *
 * This class provide helper methods to SearchApiController class.
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
use api\filmaffinity\business\FilmAffinityResources;
use api\filmaffinity\model\dto\AdvSearchDto;
use api\filmaffinity\model\dto\SearchDto;

/**
 * Class SearchACHelper.
 */
class SearchACHelper extends XsgaAbstractClass
{
    
    
    /**
     * Validates if parameter exists in array.
     * 
     * @param string $param Parameter string to validate.
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
        
        // Get FilmAffinityResources instance.
        $resources = new FilmAffinityResources();
        
        // Set array to search, array key name, error message and error number.
        switch ($type) {
            
            case 'genre':
                
                // Logger.
                $this->logger->debug('Genre code validation');
                
                // Set common variables.
                $arraySearch = json_decode($resources->getResourceFile('json', 'genres'), true);
                $arrayKey    = 'genre_code';
                $errorMsg    = "Code \"$param\" is not a valid genre code";
                $errorNum    = 201;
                
                break;
                
            case 'country':
                
                // Logger.
                $this->logger->debug('Country code validation');
                
                // Set common variables.
                $arraySearch = json_decode($resources->getResourceFile('json', 'countries'), true);
                $arrayKey    = 'country_code';
                $errorMsg    = "Code \"$param\" is not a valid country code";
                $errorNum    = 202;
                
                break;
                
            default:
                
                // Set common variables.
                $arraySearch = array();
                $arrayKey    = '';
                $errorMsg    = 'Validation type not valid';
                $errorNum    = 101;
                
                // Logger.
                $this->logger->warn($errorMsg);
            
        }//end switch
        
        // Set exists flag to false.
        $exists = false;
        
        // Search parameter code.
        foreach ($arraySearch as $array) {
            if ($array[$arrayKey] === $param) {
                $exists = true;
                break;
            }//end if
        }//end foreach
        
        if (!$exists && $param <> '') {
            
            // Logger.
            $this->logger->debugValidationKO();
            $this->logger->error($errorMsg);
            
            throw new XsgaValidationException($errorMsg, $errorNum);
            
        }//end if
        
        // Logger.
        $this->logger->debugValidationOK();
        $this->logger->debugEnd();
        
    }//end valParamIsValid()
    
    
    /**
     * Get AdvSearchDto from input data array.
     * 
     * @param array $data Input data array.
     * 
     * @return AdvSearchDto
     * 
     * @access public
     */
    public function getAdvSearchDto(array $data) : AdvSearchDto
    {
        // Logger.
        $this->logger->debugInit();
        
        // New DTO instance.
        $dto = new AdvSearchDto();
        
        // Populates DTO.
        $dto->searchText            = $data['text'];
        $dto->searchTypeTitle       = $data['title'];
        $dto->searchTypeDirector    = $data['director'];
        $dto->searchTypeCast        = $data['cast'];
        $dto->searchTypeScreenplay  = $data['screenplay'];
        $dto->searchTypePhotography = $data['photography'];
        $dto->searchTypeSoundtrack  = $data['soundtrack'];
        $dto->searchTypeProducer    = $data['producer'];
        $dto->searchGenre           = $data['genre'];
        $dto->searchCountry         = $data['country'];
        $dto->searchYearFrom        = $data['year_from'];
        $dto->searchYearTo          = $data['year_to'];
        
        // Logger.
        $this->logger->debugEnd();
        
        return $dto;
        
    }//end getAdvSearchDto()
    
    
    /**
     * Get SearchDto from input data array.
     *
     * @param array $data Input data array.
     *
     * @return SearchDto
     *
     * @access public
     */
    public function getSearchDto(array $data) : SearchDto
    {
        // Logger.
        $this->logger->debugInit();
        
        // New DTO instance.
        $dto = new SearchDto();
        
        // Populates DTO.
        $dto->searchText = $data['text'];
        
        // Logger.
        $this->logger->debugEnd();
        
        return $dto;
        
    }//end getSearchDto()
    
    
}//end SearchACHelper class
