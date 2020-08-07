<?php
/**
 * SearchACHelper.
 *
 * This class provide helper methods to SearchApiController class..
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
use api\business\FilmAffinityResources;
use api\model\dto\AdvSearchDto;
use api\model\dto\SearchDto;

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
     * @throws XsgaValidationException Not a valid genre.
     * 
     * @return void
     * 
     * @access public
     */
    public function valGenre($genreParam)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        // Get FilmAffinityResources instance.
        $resources = new FilmAffinityResources();
        
        // Get genres array from JSON file.
        $genresArray = json_decode($resources->getJsonFile('genres'), true);
        
        // Set exists flag to false.
        $exists = false;
        
        // Search genre code.
        foreach ($genresArray as $genre) {
            if ($genre['genre_code'] === $genreParam) {
                $exists = true;
                break;
            }//end if
        }//end foreach
        
        if (!$exists && $genreParam <> '') {
            
            // Error message.
            $errorMsg = 'Code "'.$genreParam.'" is not a valid genre code';
            
            // Logger.
            $this->logger->debugValidationKO();
            $this->logger->error($errorMsg);
            
            throw new XsgaValidationException($errorMsg, 103);
            
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
     * @throws XsgaValidationException Not a valid country.
     *
     * @return void
     *
     * @access public
     */
    public function valCountry($countryParam)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        // Get FilmAffinityResources instance.
        $resources = new FilmAffinityResources();
        
        // Get genres array from JSON file.
        $countriesArray = json_decode($resources->getJsonFile('countries'), true);
        
        // Set exists flag to false.
        $exists = false;
        
        // Search genre code.
        foreach ($countriesArray as $country) {
            if ($country['country_code'] === $countryParam) {
                $exists = true;
                break;
            }//end if
        }//end foreach
        
        if (!$exists && $countryParam <> '') {
            
            // Error message.
            $errorMsg = 'Code "'.$countryParam.'" is not a valid country code';
            
            // Logger.
            $this->logger->debugValidationKO();
            $this->logger->error($errorMsg);
            
            throw new XsgaValidationException($errorMsg, 104);
            
        }//end if
        
        // Logger.
        $this->logger->debugValidationOK();
        $this->logger->debugEnd();
        
    }//end valCountry()
    
    
    /**
     * Get AdvSearchDto from input data array.
     * 
     * @param array $data Input data array.
     * 
     * @return AdvSearchDto
     * 
     * @access public
     */
    public function getAdvSearchDto(array $data)
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
    public function getSearchDto(array $data)
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
