<?php
/**
 * SearchApiController.
 *
 * This class manages all API petitions from SEARCH module.
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
namespace api\controller;

/**
 * Import namespaces.
 */
use xsgaphp\api\controller\XsgaAbstractApiController;
use api\controller\helpers\SearchACHelper;
use api\business\XsgaFilmAffinity;

/**
 * Class SearchApiController.
 */
class SearchApiController extends XsgaAbstractApiController
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
     * Do search POST method.
     * 
     * @api
     * 
     * @return void
     * 
     * @access public
     */
    public function postDoSearch()
    {
        
        // Logger.
        $this->logger->debugInit();
        
        /*
         * JSON INPUT:
         * 
         * {
         *   "text": "<search_text>"
         * }
         * 
         */
        
        // Get input data.
        $data = $this->getInputData();
        
        // Validates input data.
        $this->postDoSearchValidations($data);
        
        // Get AdvSearchDto.
        $dto = $this->helper->getSearchDto($data);
        
        // Do search.
        $searchResults = $this->filmAffinity->search($dto);
        
        // Get response.
        $this->getResponse($searchResults);
        
        // Logger.
        $this->logger->debugEnd();
        
    }//end postDoSearch()
    
    
    /**
     * Do advanced search POST method.
     * 
     * @api
     * 
     * @return void
     * 
     * @access public
     */
    public function postDoAdvSearch()
    {
        
        // Logger.
        $this->logger->debugInit();
        
        /*
         * JSON INPUT:
         * 
         * {
         *   "text": "<search_text>",
         *   "title": true|false,
         *   "director": true|false,
         *   "cast": true|false,
         *   "screenplay": true|false,
         *   "photography": true|false,
         *   "soundtrack": true|false,
         *   "producer": true|false,
         *   "country": "<country_code>",
         *   "genre": "<genre_code>",
         *   "year_from": "<year>",
         *   "year_to": "<year>"
         * }
         * 
         */
        
        // Get input data.
        $data = $this->getInputData();
        
        // Validate input data.
        $this->postDoAdvSearchValidations($data);
        
        // Get AdvSearchDto.
        $dto = $this->helper->getAdvSearchDto($data);
        
        // Do advanced search.
        $searchResults = $this->filmAffinity->advancedSearch($dto);
        
        // Get response.
        $this->getResponse($searchResults);
        
        // Logger.
        $this->logger->debugEnd();
        
    }//end postDoAdvSearch()
    
    
    /**
     * Validates input data for do_search API endpoint.
     * 
     * @param array $data
     * 
     * @return void
     *
     * @access private
     */
    private function postDoSearchValidations(array $data)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        // Validates input data..
        $this->valNumberOfParams($data, 1);
        $this->valExistsParam($data, 'text');
        $this->valParamLength($data['text'], 'text', 2);
        
        // Logger.
        $this->logger->debugEnd();
                
    }//end postDoSearchValidations()
    
    
    /**
     * Validates input data for do_adv_search API endpoint.
     * 
     * @param array $data
     *
     * @return void
     *
     * @access private
     */
    private function postDoAdvSearchValidations(array $data)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        // Validate input data.
        $this->valNumberOfParams($data, 12);
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
        $this->valParamLength($data['text'], 'text', 2);
        $this->valParamIsBoolean($data['title']);
        $this->valParamIsBoolean($data['director']);
        $this->valParamIsBoolean($data['cast']);
        $this->valParamIsBoolean($data['screenplay']);
        $this->valParamIsBoolean($data['photography']);
        $this->valParamIsBoolean($data['soundtrack']);
        $this->valParamIsBoolean($data['producer']);
        $this->valParamIsInteger($data['year_from']);
        $this->valParamIsInteger($data['year_to']);
        $this->helper->valGenre($data['genre']);
        $this->helper->valCountry($data['country']);
        
        // Logger.
        $this->logger->debugEnd();
        
    }//end postDoAdvSearchValidations()
    
    
}//end SearchApiController class
