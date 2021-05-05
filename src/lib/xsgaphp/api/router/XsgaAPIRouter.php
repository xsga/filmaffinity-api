<?php
/**
 * XsgaAPIRouter.
 *
 * This file contains the XsgaAPIRouter class.
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
namespace xsgaphp\api\router;

/**
 * Import dependencies.
 */
use xsgaphp\core\XsgaAbstractClass;
use xsgaphp\api\dto\ApiErrorDevDto;
use xsgaphp\api\dto\ApiErrorDto;
use xsgaphp\exceptions\XsgaValidationException;
use xsgaphp\exceptions\XsgaPageNotFoundException;
use xsgaphp\utils\XsgaLangFiles;
use xsgaphp\utils\XsgaUtil;

/**
 * XsgaAPIRouter class.
 * 
 * This class it's a API front controller. It dispatches all REST petitions to appropiate controllers.
 */
class XsgaAPIRouter extends XsgaAbstractClass
{

    /**
     * Request.
     * 
     * @var string
     * 
     * @access protected
     */
    protected $request = '';

    /**
     * HTTP request method.
     * 
     * @var string
     * 
     * @access protected
     */
    protected $requestMethod;
    
    /**
     * Request array.
     *
     * @var array
     *
     * @access protected
     */
    protected $requestArray;
    
    /**
     * Request filters.
     *
     * @var array
     *
     * @access protected
     */
    protected $requestFilters;

    /**
     * Request body.
     * 
     * @var array
     * 
     * @access protected
     */
    protected $requestBody;

    /**
     * Routes.
     * 
     * @var array
     * 
     * @access protected
     */
    protected $routes;


    /**
     * Dispatch petition.
     * 
     * @param string $url    URL.
     * @param string $method HTTP request method.
     * 
     * @throws XsgaValidationException
     * 
     * @return void
     * 
     * @access public
     */
    public function dispatchPetition(string $url, string $method) : void
    {
        // Logger.
        $this->logger->debugInit();

        // Loads routes.
        $this->loadRoutes();

        // Validates routes.
        if (empty($this->routes)) {
            throw new XsgaValidationException('Routes not loaded', 102);
        }//end if

        // Set request method.
        $this->requestMethod = strtoupper($method);
        
        // Get data from url.
        $this->getRequestFromUrl($url);
        
        // Get request body.
        $this->getRequestBody();

        // Get route.
        $route = $this->getRoute();

        // Dispatch petition.
        $this->dispatch($route);
        
        // Logger.
        $this->logger->debugEnd();
        
    }//end dispatchPetition()
    
    
    /**
     * Dispatch API error.
     * 
     * @param integer $code  Error code.
     * @param string  $file  Error file.
     * @param integer $line  Error line.
     * @param string  $trace Error trace.
     * 
     * @return void
     * 
     * @access public
     */
    public function dispatchError(int $code, string $file, int $line, string $trace) : void
    {
        // Logger.
        $this->logger->debugInit();
        
        // Get error literals.
        $lang = XsgaLangFiles::load();
        
        // Set error message.
        $message = isset($lang['errors']['error_'.$code]) ? $lang['errors']['error_'.$code] : 'Error message not defined';
        
        if ($_ENV['ENVIRONMENT'] === 'dev') {
            
            // Set out error DTO.
            $errorDto          = new ApiErrorDevDto();
            $errorDto->code    = $code;
            $errorDto->message = $message;
            $errorDto->file    = $file;
            $errorDto->line    = $line;
            $errorDto->trace   = $trace;
            
        } else {
            
            // Set out error DTO.
            $errorDto          = new ApiErrorDto();
            $errorDto->code    = $code;
            $errorDto->message = $message;
            
        }//end if
        
        // Clean output buffer.
        ob_clean();
        
        // Set response code.
        http_response_code(500);

        // Set response headers.
        self::getHeaders();

        // Set response body.
        echo json_encode($errorDto);
        
        // Logger.
        $this->logger->debugEnd();
        
    }//end dispatchError()


    /**
     * Get HTTP headers.
     * 
     * @return void
     * 
     * @access public
     */
    public static function getHeaders() : void
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
                
    }//end getHeaders()


    /**
     * Load routes.
     * 
     * @return void
     * 
     * @throws XsgaFileNotFoundException
     * 
     * @access private
     */
    private function loadRoutes() : void
    {
        // Logger.
        $this->logger->debugInit();

        // Routes location.
        $routes = XsgaUtil::getPathTo('config').'routes.json';

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
     * @return void
     *
     * @access private
     */
    private function getRequestFromUrl(string $requestUri) : void
    {
    
        // Logger.
        $this->logger->debugInit();
        
        // Removes the directory path we don't want.
        $req = rtrim(str_replace($_ENV['URL_PATH'], '', $requestUri), '/');

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
     * @access private
     */
    private function getRequestBody() : void
    {
        // Logger.
        $this->logger->debugInit();

        // Initializes request.
        $this->requestBody = array();

        if (in_array($this->requestMethod, array('POST', 'PUT', 'PATCH'))) {
            
            // Get JSON body from request.
            $bodyJson = file_get_contents('php://input');

            // Get array body from JSON.
            $bodyArray = json_decode($bodyJson, true);

            if (empty($bodyJson)) {

                // Set empty array.
                $this->requestBody = array();
                
                // Logger.
                $this->logger->warn("$this->requestMethod request without body data");

            } else if (!empty($bodyJson) && empty($bodyArray)) {

                // Set empty array.
                $this->requestBody = array();
                
                // Logger.
                $this->logger->error("Request body data not in JSON format");

            } else {

                // Set body array.
                $this->requestBody = $bodyArray;

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
     * @access private
     */
    private function getRoute() : array
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
                    $this->logger->debug("Found route for request (\"$route[name]\")");

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
     * @access private
     */
    private function dispatch(array $route) : void
    {
        
        // Logger.
        $this->logger->debugInit();

        // Set status and namespace.
        $status    = false;
        $namespace = "api\\$_ENV[VENDOR]\\controller\\";
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
                $this->logger->error("Method \"$route[method]\" not found in class \"$className\"");

            }//end if
            
        } else {

            // Logger.
            $this->logger->error("Class \"$className\" not found");

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


}//end XsgaAPIRouter class
