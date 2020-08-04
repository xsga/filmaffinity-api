<?php
/**
 * XsgaAbstractRouter.
 *
 * This file contains the XsgaAbstract Router class.
 * 
 * PHP version 7
 *
 * @author  xsga <xsegales@outlook.com>
 * @version 1.0.0
 */
 
/**
 * Namespace.
 */
namespace xsgaphp\mvc;

/**
 * Import namespaces.
 */
use xsgaphp\exceptions\XsgaPageNotFoundException;

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
     * Request array.
     *
     * @var array
     *
     * @access public
     */
    public $requestArray = array();
    
    /**
     * Controller name.
     *
     * @var string
     *
     * @access private
     */
    public $controller = '';
    
    /**
     * Method name.
     *
     * @var string
     *
     * @access private
     */
    public $method = '';
    
    /**
     * Method parameters.
     *
     * @var array
     *
     * @access private
     */
    public $parameters = array();
    
    
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
        
        if (URL_PATH !== '/') {
             
            // Remove the directory path we don't want.
            $req = str_replace(URL_PATH, '', $requestUri);
             
        } else {
            
            // Trim request.
            $req = ltrim($requestUri, '/');
             
        }//end if
         
        // Filter request.
        $req = filter_var($req, FILTER_SANITIZE_URL);
         
        // Split the path by '/'.
        $reqArray = explode('/', $req);
    
        // Delete all null, false or empty strings from array.
        $reqArray = array_filter($reqArray, 'strlen');
    
        // Set request and requestArray.
        $this->request      = $req;
        $this->requestArray = array_values($reqArray);
        
        // Logger.
        $this->logger->debugEnd();
    
    }//end getRequestFromUrl()
    
    
    /**
     * Function dispatch.
     *
     * This function dispatchs events to appropiate controller.
     * 
     * @param string $mode Mode: app or api.
     *
     * @return void
     *
     * @throws XsgaPageNotFoundException Page not found.
     *
     * @access public
     */
    public function dispatch($mode='app')
    {
        
        // Logger.
        $this->logger->debugInit();
        
        // Set status and namespace.
        $status    = '';
        $namespace = '\\'.$mode.'\\controller\\';
        
        // If exists class, continues.
        if (class_exists($namespace.$this->controller) === true) {
            
            // If method it's empty, continues.
            if (empty($this->method) === true) {
                
                // Call to class controller.
                $controller = new $namespace.$this->controller;
                
                $status = 'ok';
                
            } else {
                
                // If method's class exists, continues.
                if (method_exists($namespace.$this->controller, $this->method) === true) {
                    
                    // Call to class controller.
                    $class      = $namespace.$this->controller;
                    $controller = new $class;
                    
                    if (empty($this->parameters) === true) {
                        
                        // Call to class method.
                        call_user_func(array($controller, $this->method));
                        
                    } else {
                        
                        // Call to class method.
                        call_user_func(array($controller, $this->method), $this->parameters);
                        
                    }//end if
                    
                    $status = 'ok';
                    
                }//end if
                
            }//end if
            
        }//end if
        
        // Evaluates status variable.
        if ($status === '') {
            
            // Set error message.
            $log = 'Page not found';
            
            // Logger.
            $this->logger->error($log);
            
            throw new XsgaPageNotFoundException($log, 112);
            
        }//end if
        
        // Logger.
        $this->logger->debugEnd();
        
    }//end dispatch()
    

}//end XsgaAbstractRouter class
