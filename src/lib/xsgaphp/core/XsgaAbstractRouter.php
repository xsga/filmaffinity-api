<?php
/**
 * XsgaAbstractRouter.
 *
 * This file contains the XsgaAbstract Router class.
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
namespace xsgaphp\core;

/**
 * Import dependencies.
 */
use xsgaphp\core\XsgaAbstractClass;
use xsgaphp\exceptions\XsgaPageNotFoundException;
use xsgaphp\exceptions\XsgaValidationException;
use xsgaphp\utils\XsgaUtil;

/**
 * XsgaAbstractRouter class.
 * 
 * This class it's a front controller. It dispatches all HTTP petitions to appropiate controllers.
 */
class XsgaAbstractRouter extends XsgaAbstractClass
{
    
    /**
     * Request.
     * 
     * @var string
     * 
     * @access public
     */
    public $request = '';

    /**
     * HTTP request method.
     * 
     * @var string
     * 
     * @access public
     */
    public $requestMethod;
    
    /**
     * Request array.
     *
     * @var array
     *
     * @access public
     */
    public $requestArray;
    
    /**
     * Request filters.
     *
     * @var array
     *
     * @access public
     */
    public $requestFilters;

    /**
     * Request body.
     * 
     * @var array
     * 
     * @access public
     */
    public $requestBody;

    /**
     * Routes.
     * 
     * @var array
     * 
     * @access public
     */
    public $routes;


    /**
     * Constructor.
     * 
     * @access public
     */
    public function __construct()
    {
        // Executes parent constructor.
        parent::__construct();

        // Loads routes.
        $this->loadRoutes();

    }//end __construct()


    /**
     * Load routes.
     * 
     * @return void
     * 
     * @throws XsgaFileNotFoundException
     * 
     * @access private
     */
    private function loadRoutes()
    {
        // Logger.
        $this->logger->debugInit();

        // Routes location.
        $routes = realpath(dirname(__FILE__)).XsgaUtil::getPathTo(4, array('config')).'routes.json';

        if (file_exists($routes)) {
            
            // Loads routes file.
            $this->routes = json_decode(file_get_contents($routes), true);

            // Logger.
            $this->logger->debug('Routes JSON file loads successfully');

        } else {

            // Logger.
            $this->logger->error('Error loading routes JSON file. File not found');

        }//end if

        // Logger.
        $this->logger->debugEnd();

    }//end loadRoutes()
    
    
    /**
     * Get request from URL.
     *
     * @param string $requestUri Request URI.
     *
     * @return array
     *
     * @access public
     */
    public function getRequestFromUrl($requestUri)
    {
    
        // Logger.
        $this->logger->debugInit();
        
        if ($_ENV['URL_PATH'] !== '/') {
             
            // Removes the directory path we don't want.
            $req = str_replace($_ENV['URL_PATH'], '', $requestUri);
             
        }//end if
        
        // Trim request.
        $req = rtrim($req, '/');

        // Filter request.
        $req = filter_var($req, FILTER_SANITIZE_URL);

        $reqAux = explode('?', $req);
         
        // Split the path by '/'.
        $reqArray = explode('/', $reqAux[0]);
    
        // Delete all null, false or empty strings from array.
        $reqArray = array_filter($reqArray, 'strlen');
    
        // Set request, requestArray, requestFilters and requestParams.
        $this->request        = rtrim($reqAux[0], '/');
        $this->requestArray   = array_values($reqArray);
        $this->requestFilters = $_GET;
        
        // Logger.
        $this->logger->debugEnd();
    
    }//end getRequestFromUrl()


    /**
     * Get request body.
     * 
     * @return void
     * 
     * @access public
     */
    public function getRequestBody()
    {
        // Logger.
        $this->logger->debugInit();

        // Initializes request.
        $this->requestBody = array();

        if (in_array($this->requestMethod, array('POST', 'PUT', 'PATCH'))) {
            
            $this->requestBody = json_decode(file_get_contents('php://input'), true);

            if (empty($this->requestBody)) {

                $this->requestBody = array();
                
                // Logger.
                $this->logger->warn($this->requestMethod.' request without body data');

            }//end if

        }//end if  

        // Logger.
        $this->logger->debugEnd();

    }//end getRequestBody()


    /**
     * Get route.
     * 
     * @return array
     * 
     * @throws XsgaValidationException
     * 
     * @access public
     */
    public function getRoute()
    {
        // Logger.
        $this->logger->debugInit();

        // Initializes output.
        $out = array();

        // Process routes.
        foreach ($this->routes as $route) {

            $regex = '';

            if (strtoupper($route['http_method']) === $this->requestMethod) {
                
                // Regular expression for slug.
                if (is_numeric(stripos($route['pattern'], ':slug'))) {
                    $regex = '#'.str_replace(':slug', '[a-z]+(?:-[a-z]+)*$', $route['pattern']).'#';
                }//end if

                // Regular expression for id.
                if (is_numeric(stripos($route['pattern'], ':id'))) {
                    $regex = '#'.str_replace(':id', '[1-9][0-9]*$', $route['pattern']).'#';
                }//end if

                // Regular expression for text.
                if (is_numeric(stripos($route['pattern'], ':text'))) {
                    $regex = '#'.str_replace(':text', '[a-zA-Z]*$', $route['pattern']).'#';
                }//end if

                if (empty($regex)) {
                    $regex = '#'.$route['pattern'].'#';
                }//end if
                
                // Match with request.
                if (preg_match($regex, $this->request) === 1) {
                    
                    // Logger.
                    $this->logger->debug('Found route for request ("'.$route['name'].'")');

                    // Set output.
                    $out = $route;

                    break;

                }//end if

            }//end if

        }//end foreach

        // Error, no found route.
        if (empty($out)) {

            // Logger.
            $this->logger->debugValidationKO();
            $this->logger->error('Not found route for request');
                                    
            throw new XsgaValidationException('Invalid request', 104);

        }//end if

        // Logger.
        $this->logger->debugValidationOK();
        $this->logger->debugEnd();

        return $out;

    }//end getRoute()
    
    
    /**
     * Function dispatch.
     *
     * This function dispatchs events to appropiate controller.
     * 
     * @param array $route Route.
     *
     * @return void
     *
     * @throws XsgaPageNotFoundException Page not found.
     *
     * @access public
     */
    public function dispatch(array $route)
    {
        
        // Logger.
        $this->logger->debugInit();

        // Set status and namespace.
        $status    = false;
        $namespace = 'api\\'.$_ENV['VENDOR'].'\\controller\\';
        $className = $namespace.$route['controller'];
        
        // If exists route class, continues.
        if (class_exists($className)) {
            
            // If route method's class exists, continues.
            if (method_exists($className, $route['method'])) {
                    
                // Call to class controller.
                $controller = new $className;
                
                // Call to class method.
                call_user_func(
                    array(
                        $controller, 
                        $route['method']
                    ), 
                    $this->requestArray, 
                    $this->requestFilters, 
                    $this->requestBody
                );
                
                $status = true;
                
            } else {

                // Logger.
                $this->logger->error('Method "'.$route['method'].'" not found in class "'.$className.'"');

            }//end if
            
        } else {

            // Logger.
            $this->logger->error('Class "'.$className.'" not found');

        }//end if
        
        // Evaluates status variable.
        if (!$status) {
            
            // Set error message.
            $log = 'Resource not found';
            
            // Logger.
            $this->logger->error($log);
            
            throw new XsgaPageNotFoundException($log, 103);
            
        }//end if
        
        // Logger.
        $this->logger->debugEnd();
        
    }//end dispatch()
    

}//end XsgaAbstractRouter class
