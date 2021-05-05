<?php
/**
 * FilmController.
 *
 * This class manages all API petitions from FILM module.
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
use api\filmaffinity\business\XsgaFilmAffinity;

/**
 * Class FilmController.
 */
class FilmController extends XsgaAbstractApiController
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
     * Get film information.
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
    public function getFilm(array $request, array $filters, array $body) : void
    {
        // Logger.
        $this->logger->debugInit();
        
        // TODO: tratar parámetros.
        unset($request[0]);
        $request = array_values($request);

        // Validates input data.
        $this->getFilmValidations($request);
                
        // Load film by fimAffinity ID.
        $filmDto = $this->filmAffinity->loadFilm($request[0]);
        
        // Get response.
        $this->getResponse($filmDto);
        
        // Logger.
        $this->logger->debugEnd();
        
    }//end getFilm()
    
    
    /**
     * Validates input data.
     * 
     * @param array $params Parameters.
     * 
     * @return void
     * 
     * @access private
     */
    private function getFilmValidations(array $params) : void
    {
        // Logger.
        $this->logger->debugInit();
        
        // Validates number of parameters: 1 parameter expected (film ID).
        $this->valNumberOfParams($params, 1);
        
        // Validates that parameter is numeric.
        $this->valParamIsNumeric($params[0]);
        
        // Logger.
        $this->logger->debugEnd();
        
    }//end getFilmValidations()
    
    
}//end FilmController class
