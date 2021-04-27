<?php
/**
 * LoggerLocationInfo.
 * 
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements. See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 *
 *       http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 * PHP Version 8
 *
 * @package Log4php
 */

/**
 * Namespace.
 */
namespace log4php;

/**
 * The internal representation of caller location information.
 */
class LoggerLocationInfo
{
    
    /**
     * The value to return when the location information is not available.
     * 
     * @var string
     * 
     * @access public
     */
    const LOCATION_INFO_NA = 'NA';
    
    /**
     * Caller line number.
     * 
     * @var integer
     * 
     * @access protected
     */
    protected $lineNumber;
    
    /**
     * Caller file name.
     * 
     * @var string
     * 
     * @access protected
     */
    protected $fileName;
    
    /**
     * Caller class name.
     * 
     * @var string
     * 
     * @access protected
     */
    protected $className;
    
    /**
     * Caller method name.
     * 
     * @var string
     * 
     * @access protected
     */
    protected $methodName;
    
    /**
     * All the information combined.
     * 
     * @var string
     * 
     * @access protected
     */
    protected $fullInfo;
    
    
    /**
     * Instantiate location information based on a {@link PHP_MANUAL#debug_backtrace}.
     *
     * @param array $trace Trace.
     * 
     * @access public
     */
    public function __construct(array $trace)
    {
        
        if (isset($trace['line'])) {
            $this->lineNumber = $trace['line'];
        } else {
            $this->lineNumber = null;
        }//end if
        
        if (isset($trace['file'])) {
            $this->fileName = $trace['file'];
        } else {
            $this->fileName = null;
        }//end if
        
        if (isset($trace['class'])) {
            $this->className = $trace['class'];
        } else {
            $this->className = null;
        }//end if
        
        if (isset($trace['function'])) {
            $this->methodName = $trace['function'];
        } else {
            $this->methodName = null;
        }//end if
        
        $this->fullInfo  = $this->getClassName().'.'.$this->getMethodName();
        $this->fullInfo .= '('.$this->getFileName().':'.$this->getLineNumber().')';
        
    }//end __construct()
    
    
    /**
     * Returns the caller class name.
     * 
     * @return string
     * 
     * @access public
     */
    public function getClassName() : string
    {
        if ($this->className === null) {
            $out = static::LOCATION_INFO_NA;
        } else {
            $out = $this->className;
        }//end if
        
        return $out;
        
    }//end getClassName()
    
    
    /**
     * Returns the caller file name.
     * 
     * @return string
     * 
     * @access public
     */
    public function getFileName() : string
    {
        if ($this->fileName === null) {
            $out = static::LOCATION_INFO_NA;
        }else {
            $out = $this->fileName;
        }//end if
        
        return $out;
         
    }//end getFileName()
    
    
    /**
     * Returns the caller line number.
     * 
     * @return integer|string
     * 
     * @access public
     */
    public function getLineNumber() : int|string
    {
        if ($this->lineNumber === null) {
            $out = static::LOCATION_INFO_NA;
        } else {
            $out = $this->lineNumber;
        }//end if
        
        return $out;
        
    }//end getLineNumber()
    
    
    /**
     * Returns the caller method name.
     * 
     * @return string
     * 
     * @access public
     */
    public function getMethodName() : string
    {
        if ($this->methodName === null) {
            $out = static::LOCATION_INFO_NA;
        } else {
            $out = $this->methodName;
        }//end if
        
        return $out;
        
    }//end getMethodName()
    
    
    /**
     * Returns the full information of the caller.
     * 
     * @return string
     * 
     * @access public
     */
    public function getFullInfo() : string
    {
        if ($this->fullInfo === null) {
            $out = static::LOCATION_INFO_NA;
        } else {
            $out = $this->fullInfo;
        }//end if
        
        return $out;
        
    }//end getFullInfo()
    
    
}//end LoggerLocationInfo class
