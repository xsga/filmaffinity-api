<?php
/**
 * FilmApiController.
 *
 * This class manages all API petitions from FILM module.
 *
 * PHP version 7
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
 * Import namespaces.
 */
use xsgaphp\api\controller\XsgaAbstractApiController;
use api\filmaffinity\business\XsgaFilmAffinity;

/**
 * Class FilmApiController.
 */
class FilmApiController extends XsgaAbstractApiController
{
    
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
        
        // Executes parant constructor.
        parent::__construct();
        
        // Set FilmAffinity business class.
        $this->filmAffinity = new XsgaFilmAffinity();
        
    }//end __construct()
    
    
    /**
     * Get film GET method.
     * 
     * @api
     * 
     * @param array $params Filmaffinity movie ID.
     * 
     * @return void
     * 
     * @access public
     */
    public function getGetFilm(array $params = array())
    {
        
        // Logger.
        $this->logger->debugInit();
        
        // Validates input data.
        $this->getGetFilmValidations($params);
                
        // Load film by fimAffinity ID.
        $filmDto = $this->filmAffinity->loadFilm($params[0]);
        
        // Get response.
        $this->getResponse($filmDto);
        
        // Logger.
        $this->logger->debugEnd();
        
    }//end getGetFilm()
    
    
    /**
     * Validates input data.
     * 
     * @param array $params Parameters.
     * 
     * @return void
     * 
     * @access private
     */
    private function getGetFilmValidations(array $params)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        // Validates number of parameters: 1 parameter expected (film ID).
        $this->valNumberOfParams($params, 1);
        
        // Validates that parameter is numeric.
        $this->valParamIsNumeric($params[0]);
        
        // Logger.
        $this->logger->debugEnd();
        
    }//end getGetFilmeValidations()
    
    
}//end FilmApiController class
