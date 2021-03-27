<?php
/**
 * XsgaAbstractApiController.
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
     * Input data file.
     * 
     * @var string
     * 
     * @access public
     */
    public $inputData = 'php://input';
    
    
    /**
     * Get request parameters.
     *
     * @return array
     *
     * @access public
     */
    public function getInputData()
    {
        
        // Logger.
        $this->logger->debugInit();
        
        $data = json_decode(file_get_contents($this->inputData), true);
        
        if (is_null($data)) {
            $data = array();
        }//end if
        
        // Logger.
        $this->logger->debugEnd();
        
        return $data;
        
    }//end getInputData()
    
    
    /**
     * Get response.
     * 
     * @param mixed   $data       Response data.
     * @param boolean $encodeJson Flag to indicates if have to encode JSON.
     * 
     * @return void
     * 
     * @access public
     */
    public function getResponse($data, $encodeJson = true)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        // Clean output buffer.
        ob_clean();
        
        // Get HTTP headers.
        XsgaAPIRouter::getHeaders();
        
        if ($encodeJson) {
            echo json_encode($data);
        } else {
            echo $data;
        }//end if
        
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
        
        // Logger.
        $this->logger->debugInit();
        
        // Get total elements of array.
        $total = count($data);
        
        if ($total <> $expected) {
            
            // Error message.
            $errorMsg = 'Expected '.$expected.' parameter/s, '.$total.' recived';
            
            // Logger.
            $this->logger->debugValidationKO();
            $this->logger->error($errorMsg);
            
            throw new XsgaValidationException($errorMsg, 105);
            
        }//end if
        
        // Logger.
        $this->logger->debugValidationOK();
        $this->logger->debugEnd();
        
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
        
        // Logger.
        $this->logger->debugInit();
        
        if (!isset($data[$paramName])) {
            
            // Error message.
            $errorMsg = 'Expected parameter "'.$paramName.'" not found';
            
            // Logger.
            $this->logger->debugValidationKO();
            $this->logger->error($errorMsg);
            
            throw new XsgaValidationException($errorMsg, 106);
            
        }//end if
        
        // Logger.
        $this->logger->debugValidationOK();
        $this->logger->debugEnd();
        
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
        
        // Logger.
        $this->logger->debugInit();
        
        if (strlen($param) < $minLength) {
            
            // Error message.
            $errorMsg = 'Minium length of "'.$paramName.'" parameter is '.$minLength;
            
            // Logger.
            $this->logger->debugValidationKO();
            $this->logger->error($errorMsg);
            
            throw new XsgaValidationException($errorMsg, 107);
            
        }//end if
        
        // Logger.
        $this->logger->debugValidationOK();
        $this->logger->debugEnd();
        
    }//end valParamLength()
    
    
    /**
     * Validates if parametes is boolean.
     * 
     * @param mixed $param
     * 
     * @throws XsgaValidationException Parameter is not a boolean.
     * 
     * @return void
     * 
     * @access public
     */
    public function valParamIsBoolean($param)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        if (!is_bool($param)) {
            
            // Error message.
            $errorMsg = 'Parameter is not a boolean';
            
            // Logger.
            $this->logger->debugValidationKO();
            $this->logger->error($errorMsg);
            
            throw new XsgaValidationException($errorMsg, 108);
            
        }//end if
        
        // Logger.
        $this->logger->debugValidationOK();
        $this->logger->debugEnd();
        
    }//end valParamIsBoolean()
    
    
    /**
     * Validates if parameter is integer.
     * 
     * @param mixed $param
     * 
     * @throws XsgaValidationException Parameter is not an integer.
     * 
     * @return void
     * 
     * @access public
     */
    public function valParamIsInteger($param)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        if (!is_int($param) && !empty($param)) {
            
            // Error message.
            $errorMsg = 'Parameter is not an integer';
            
            // Logger.
            $this->logger->debugValidationKO();
            $this->logger->error($errorMsg);
            
            throw new XsgaValidationException($errorMsg, 109);
            
        }//end if
        
        // Logger.
        $this->logger->debugValidationOK();
        $this->logger->debugEnd();
        
    }//end valParamIsInteger()
    
    
    /**
     * Validates if parameter is numeric.
     *
     * @param mixed $param
     *
     * @throws XsgaValidationException Parameter is not numeric.
     *
     * @return void
     *
     * @access public
     */
    public function valParamIsNumeric($param)
    {
        
        // Logger.
        $this->logger->debugInit();
        
        if (!is_numeric($param) && !empty($param)) {
            
            // Error message.
            $errorMsg = 'Parameter is not numeric';
            
            // Logger.
            $this->logger->debugValidationKO();
            $this->logger->error($errorMsg);
            
            throw new XsgaValidationException($errorMsg, 110);
            
        }//end if
        
        // Logger.
        $this->logger->debugValidationOK();
        $this->logger->debugEnd();
        
    }//end valParamIsNumeric()
    
    
}//end XsgaAbstractController class
