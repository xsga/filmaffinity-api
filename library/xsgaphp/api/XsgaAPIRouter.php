<?php
/**
 * XsgaAPIRouter.
 *
 * This file contains the XsgaAPIRouter class.
 * 
 * PHP version 7
 *
 * @author  xsga <xsegales@outlook.com>
 * @version 1.0.0
 */
 
/**
 * Namespace.
 */
namespace xsgaphp\api;

/**
 * Import namespaces.
 */
use xsgaphp\mvc\XsgaAbstractRouter;
use xsgaphp\api\dto\OutErrorDto;
use xsgaphp\exceptions\XsgaValidationException;
use xsgaphp\exceptions\XsgaException;

/**
 * XsgaAPIRouter class.
 * 
 * This class it's a API front controller. It dispatches all REST petitions to appropiate controllers.
 */
class XsgaAPIRouter extends XsgaAbstractRouter
{
    
    /**
     * HTTP request method.
     * 
     * @var string
     * 
     * @access public
     */
    public $requestMethod;
    
    
    /**
     * Dispatch petition.
     * 
     * @param string $url    URL.
     * @param string $method HTTP request method.
     * 
     * @return void
     * 
     * @access public
     */
    public function dispatchPetition($url, $method)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        // Set request method.
        $this->requestMethod = $method;
        
        // Get data from url.
        $this->getRequestFromUrl($url);
        
        // Validates request.
        $this->validatesRequest();
        
        // Set controller.
        $this->controller = ucfirst($this->requestArray[0]).'ApiController';
        
        // Set method.
        $methodArray  = explode('_', $this->requestArray[1]);
        $methodArray  = array_map('ucfirst', $methodArray);
        $this->method = strtolower($method).implode('', $methodArray);
        
        // Set parameters.
        $params = $this->requestArray;
        unset($params[0]);
        unset($params[1]);
        $this->parameters = array_values($params);
        
        // Dispatch petition.
        $this->dispatch('api');
        
        // Logger.
        $this->logger->debugEnd();
        
    }//end dispatchPetition()
    
    
    /**
     * Dispatch API error.
     * 
     * @param integer $code    Error code.
     * @param string  $message Error message.
     * @param string  $file    Error file.
     * @param integer $line    Error line.
     * @param string  $trace   Error trace.
     * 
     * @return void
     * 
     * @access public
     */
    public function dispatchError($code, $message, $file, $line, $trace)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        // Set out error DTO.
        $errorDto          = new OutErrorDto();
        $errorDto->code    = $code;
        $errorDto->message = $message;
        $errorDto->file    = $file;
        $errorDto->line    = $line;
        $errorDto->trace   = $trace;
        
        // Clean output buffer.
        ob_clean();
        
        // Set response.
        http_response_code($code);
        self::getHeaders();
        echo json_encode($errorDto);
        
        // Logger.
        $this->logger->debugEnd();
        
        throw new XsgaException();
        
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
    
    
    /**
     * Validate request.
     * 
     * @return void
     * 
     * @throws XsgaValidationException
     * 
     * @access private
     */
    private function validatesRequest()
    {
        
        // Logger.
        $this->logger->debugInit();
        
        $total = count($this->requestArray);
        
        if ($total < 2) {
            
            // Error message.
            $errorMsg = 'Invalid request';
            
            // Logger.
            $this->logger->debugValidationKO();
            $this->logger->error($errorMsg);
            
            throw new XsgaValidationException($errorMsg);
            
        }//end if
        
        // Logger.
        $this->logger->debugValidationOK();
        $this->logger->debugEnd();
        
    }//end validatesRequest()


}//end XsgaAPIRouter class
