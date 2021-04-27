<?php
/**
 * LoggerThrowableInformation.
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
 * Import dependencies.
 */
use log4php\renderers\LoggerRendererException;

/**
 * The internal representation of throwables.
 */
class LoggerThrowableInformation
{
    
    /**
     * Throwable.
     * 
     * @var \Exception
     * 
     * @access private
     */
    private $throwable;
    
    /**
     * Array of throwable messages.
     * 
     * @var array
     * 
     * @access private
     */
    private $throwableArray;
    
    
    /**
     * Create a new instance.
     * 
     * @param \Exception $throwable A throwable as a exception.
     * 
     * @access public
     */
    public function __construct(\Exception $throwable)
    {
        $this->throwable = $throwable;
        
    }//end __construct()
    
    
    /**
     * Return source exception.
     * 
     * @return \Exception
     * 
     * @access public
     */
    public function getThrowable() : \Exception
    {
        return $this->throwable;
        
    }//end getThrowable()
    
    
    /**
     * Returns string representation of throwable.
     * 
     * @return array
     * 
     * @access public
     */
    public function getStringRepresentation() : array
    {
        if (!is_array($this->throwableArray)) {
            $renderer = new LoggerRendererException();
            
            $this->throwableArray = explode("\n", $renderer->render($this->throwable));
        }//end if
        
        return $this->throwableArray;
        
    }//end getStringRepresentation()
    
    
}//end LoggerThrowableInformation class
