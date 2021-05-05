<?php
/**
 * SearchController.
 *
 * This class manages all API petitions from SEARCH module.
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
use api\filmaffinity\controller\helpers\SearchACHelper;
use api\filmaffinity\business\XsgaFilmAffinity;

/**
 * Class SearchController.
 */
class SearchController extends XsgaAbstractApiController
{
    
    /**
     * Helper.
     * 
     * @var SearchACHelper
     * 
     * @access private
     */
    private $helper;
    
    /**
     * FilmAffinity business class.
     * 
     * @var XsgaFilmAffinity
     * 
     * @access private
     */
    private $filmAffinity;
    
    
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
        $this->helper = new SearchACHelper();
        
        // Set FilmAffinity business class.
        $this->filmAffinity = new XsgaFilmAffinity();
        
    }//end __construct()
    
    
    /**
     * Simple search.
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
    public function simpleSearch(array $request, array $filters, array $body) : void
    {
        // Logger.
        $this->logger->debugInit();
        
        // Validates input data.
        $this->simpleSearchValidations($body);
        
        // Get AdvSearchDto.
        $dto = $this->helper->getSearchDto($body);
        
        // Do search.
        $searchResults = $this->filmAffinity->search($dto);
        
        // Get response.
        $this->getResponse($searchResults);
        
        // Logger.
        $this->logger->debugEnd();
        
    }//end simpleSearch()
    
    
    /**
     * Advanced search.
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
    public function advancedSearch(array $request, array $filters, array $body) : void
    {
        // Logger.
        $this->logger->debugInit();
        
        // Validate input data.
        $this->advancedSearchValidations($body);
        
        // Get AdvSearchDto.
        $dto = $this->helper->getAdvSearchDto($body);
        
        // Do advanced search.
        $searchResults = $this->filmAffinity->advancedSearch($dto);
        
        // Get response.
        $this->getResponse($searchResults);
        
        // Logger.
        $this->logger->debugEnd();
        
    }//end advancedSearch()
    
    
    /**
     * Validates input data for simple search API endpoint.
     * 
     * @param array $data
     * 
     * @return void
     *
     * @access private
     */
    private function simpleSearchValidations(array $data) : void
    {
        // Logger.
        $this->logger->debugInit();
        
        // Validates number of parameters: 1 parameter expected (search text).
        $this->valNumberOfParams($data, 1);
        
        // Validates that parameter "text" exists.
        $this->valExistsParam($data, 'text');
        
        // Validates that parameter "text" is longer than 2 characters.
        $this->valParamLength($data['text'], 'text', 2);
        
        // Logger.
        $this->logger->debugEnd();
                
    }//end simpleSearchValidations()
    
    
    /**
     * Validates input data for advanced search API endpoint.
     * 
     * @param array $data
     *
     * @return void
     *
     * @access private
     */
    private function advancedSearchValidations(array $data) : void
    {
        // Logger.
        $this->logger->debugInit();
        
        // Validates number of parameters: 12 parameters expected.
        $this->valNumberOfParams($data, 12);
        
        // Validates that required parameters exists.
        $this->valExistsParam($data, 'text');
        $this->valExistsParam($data, 'title');
        $this->valExistsParam($data, 'director');
        $this->valExistsParam($data, 'cast');
        $this->valExistsParam($data, 'screenplay');
        $this->valExistsParam($data, 'photography');
        $this->valExistsParam($data, 'soundtrack');
        $this->valExistsParam($data, 'producer');
        $this->valExistsParam($data, 'country');
        $this->valExistsParam($data, 'genre');
        $this->valExistsParam($data, 'year_from');
        $this->valExistsParam($data, 'year_to');
        
        // Validates search text length.
        $this->valParamLength($data['text'], 'text', 2);
        
        // Validates boolean parameters.
        $this->valParamIsBoolean($data['title']);
        $this->valParamIsBoolean($data['director']);
        $this->valParamIsBoolean($data['cast']);
        $this->valParamIsBoolean($data['screenplay']);
        $this->valParamIsBoolean($data['photography']);
        $this->valParamIsBoolean($data['soundtrack']);
        $this->valParamIsBoolean($data['producer']);
        
        // Validates integer parameters.
        $this->valParamIsInteger($data['year_from']);
        $this->valParamIsInteger($data['year_to']);
        
        // Validates that "genre" parameter is valid.
        $this->helper->valParamIsValid($data['genre'], 'genre');
        
        // Validates that "country" parameter is valid.
        $this->helper->valParamIsValid($data['country'], 'country');
        
        // Logger.
        $this->logger->debugEnd();
        
    }//end advancedSearchValidations()
    
    
}//end SearchController class
