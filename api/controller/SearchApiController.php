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

/**
 * Class SearchApiController.
 */
class SearchApiController extends XsgaAbstractApiController
{
    
    /**
     * Helper.
     * 
     * @var SearchACHelper
     */
    public $helper;
    
    
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
        
    }//end __construct()
    
    
    /**
     * Do search POST method.
     * 
     * @return void
     * 
     * @access public
     */
    public function postDoSearch()
    {
        
        /*
         * JSON INPUT:
         * 
         * {
         *   "search": "<search_text>"
         * }
         * 
         */
        
        // Get input data.
        $data = $this->getInputData();
        
        // Validates input data..
        $this->valNumberOfParams($data, 1);
        $this->valExistsParam($data, 'search');
        $this->valParamLength($data['search'], 'search', 2);
        
        // Get response.
        $this->getResponse($data);
        
    }//end postDoSearch()
    
    
    /**
     * Do advanced search POST method.
     * 
     * @return void
     * 
     * @access public
     */
    public function postDoAdvSearch()
    {
        
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
        $this->helper->valGenre($data['genre']);
        $this->helper->valCountry($data['country']);
        
        // Get response.
        $this->getResponse($data);
        
    }//end postDoAdvSearch()
    
    
}//end SearchApiController class
