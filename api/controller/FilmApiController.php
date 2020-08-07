<?php
/**
 * FilmApiController.
 *
 * This class manages all API petitions from FILM module.
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
namespace api\controller;

/**
 * Import namespaces.
 */
use xsgaphp\api\controller\XsgaAbstractApiController;
use api\business\XsgaFilmAffinity;

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
                
        // Load movie.
        $filmDto = $this->filmAffinity->loadFilm($params[0]);
        
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
        
        $this->valNumberOfParams($params, 1);
        $this->valParamIsNumeric($params[0]);
        
        // Logger.
        $this->logger->debugEnd();
        
    }//end getGetFilmeValidations()
    
    
}//end FilmApiController class