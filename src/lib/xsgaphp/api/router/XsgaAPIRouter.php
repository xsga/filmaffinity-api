<?php
/**
 * XsgaAPIRouter.
 *
 * This file contains the XsgaAPIRouter class.
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
namespace xsgaphp\api\router;

/**
 * Import dependencies.
 */
use xsgaphp\core\XsgaAbstractRouter;
use xsgaphp\exceptions\XsgaValidationException;
use xsgaphp\api\dto\ApiErrorDevDto;
use xsgaphp\api\dto\ApiErrorDto;
use xsgaphp\utils\XsgaLangFiles;

/**
 * XsgaAPIRouter class.
 * 
 * This class it's a API front controller. It dispatches all REST petitions to appropiate controllers.
 */
class XsgaAPIRouter extends XsgaAbstractRouter
{
    
    
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
    public function dispatchPetition($url, $method)
    {
        // Logger.
        $this->logger->debugInit();

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
    public function dispatchError($code, $file, $line, $trace)
    {
        // Logger.
        $this->logger->debugInit();
        
        // Get error literals.
        $lang = XsgaLangFiles::load();
        
        // Set error message.
        $message = isset($lang['error_'.$code]) ? $lang['error_'.$code] : 'Error message not defined';
        
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
    public static function getHeaders()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
                
    }//end getHeaders()


}//end XsgaAPIRouter class
