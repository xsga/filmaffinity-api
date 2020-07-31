<?php
/**
 * XsgaAbstractApiController.
 *
 * PHP version 7
 *
 * @author  xsga <xsegales@outlook.com>
 * @version 1.0.0
 */
 
/**
 * Namespace.
 */
namespace xsgaphp\api\controller;

/**
 * Import namespaces.
 */
use xsgaphp\mvc\XsgaAbstractClass;
use xsgaphp\api\XsgaAPIRouter;
use xsgaphp\exceptions\XsgaValidationException;

/**
 * XsgaAbstractApiController class.
 *
 * This abstract class defines the controller's pattern.
 */
abstract class XsgaAbstractApiController extends XsgaAbstractClass
{
    
    
    /**
     * Get request parameters.
     *
     * @return array
     *
     * @access public
     */
    public function getInputData()
    {
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (is_null($data)) {
            $data = array();
        }//end if
        
        return $data;
        
    }//end getInputData()
    
    
    /**
     * Get response.
     * 
     * @param mixed $data response data.
     * 
     * @return void
     * 
     * @access public
     */
    public function getResponse($data)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        ob_clean();
        XsgaAPIRouter::getHeaders();
        echo json_encode($data);
        
        // Logger.
        $this->logger->debugEnd();
        
    }//end getResponse()
    
    
    /**
     * Validates number of parameters.
     * 
     * @param array   $data
     * @param integer $expected
     * 
     * @throws XsgaValidationException Expected X parameter/s, Y recived.
     * 
     * @return void
     * 
     * @access public
     */
    public function valNumberOfParams(array $data, $expected)
    {
        
        $total = count($data);
        
        if ($total <> $expected) {
            
            // Error message.
            $errorMsg = 'Expected '.$expected.' parameter/s, '.$total.' recived';
            
            // Logger.
            $this->logger->error($errorMsg);
            
            throw new XsgaValidationException($errorMsg);
            
        }//end if
        
    }//end valNumberOfParams()
    
    
    /**
     * Validates if exists parameter.
     * 
     * @param array  $data
     * @param string $paramName
     * 
     * @throws XsgaValidationException Expected parameter "XX" not found.
     * 
     * @return void
     * 
     * @access public
     */
    public function valExistsParam(array $data, $paramName)
    {
        
        if (!isset($data[$paramName])) {
            
            // Error message.
            $errorMsg = 'Expected parameter "'.$paramName.'" not found';
            
            // Logger.
            $this->logger->error($errorMsg);
            
            throw new XsgaValidationException($errorMsg);
            
        }//end if
        
    }//end valExistsParam()
    
    
    /**
     * Validates parameter length.
     * 
     * @param string  $param
     * @param string  $paramName
     * @param integer $minLength
     * 
     * @throws XsgaValidationException Minium length of "XX" parameter is Y.
     * 
     * @return void
     * 
     * @access public
     */
    public function valParamLength($param, $paramName, $minLength)
    {
        
        if (strlen($param) < $minLength) {
            
            // Error message.
            $errorMsg = 'Minium length of "'.$paramName.'" parameter is '.$minLength;
            
            // Logger.
            $this->logger->error($errorMsg);
            
            throw new XsgaValidationException($errorMsg);
            
        }//end if
        
    }//end valParamLength()
    
    
    /**
     * Validates if parametes it's boolean.
     * 
     * @param mixed $param
     * 
     * @return void
     * 
     * @access public
     */
    public function valParamIsBoolean($param)
    {
        
        if (!is_bool($param)) {
            
            // Error message.
            $errorMsg = 'Parameter it\s not a boolean';
            
            // Logger.
            $this->logger->error($errorMsg);
            
            throw new XsgaValidationException($errorMsg);
            
        }//end if
        
    }//end valParamIsBoolean()
    
    
}//end XsgaAbstractController class
