<?php
/**
 * LoggerRoot.
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
 * PHP Version 5
 *
 * @package Log4php
 */

namespace log4php;

/**
 * The root logger.
 */
class LoggerRoot extends Logger
{
    
    
    /**
     * Constructor.
     *
     * @param LoggerLevel $level Initial log level.
     * 
     * @access public
     */
    public function __construct(LoggerLevel $level=null)
    {
        parent::__construct('root');
        
        if ($level === null) {
            $level = LoggerLevel::getLevelAll();
        }//end if
        
        $this->setLevel($level);
        
    }//end __construct()
    
    
    /**
     * Get effective level.
     * 
     * @return LoggerLevel
     * 
     * @access public
     */
    public function getEffectiveLevel()
    {
        
        return $this->getLevel();
        
    }//end getEffectiveLevel()
    
    
    /**
     * Override level setter to prevent setting the root logger's level to null. Root logger must always have a level.
     * 
     * @param LoggerLevel $level Level.
     * 
     * @return void
     * 
     * @access public
     */
    public function setLevel(LoggerLevel $level=null)
    {
        if (isset($level) === true) {
            parent::setLevel($level);
        } else {
            trigger_error('log4php: Cannot set LoggerRoot level to null.', E_USER_WARNING);
        }//end if
        
    }//end setLevel()
    
    
    /**
     * Override parent setter. Root logger cannot have a parent.
     * 
     * @param Logger $parent Parent.
     * 
     * @return void
     * 
     * @access public
     */
    public function setParent(Logger $parent)
    {
        
        trigger_error('log4php: LoggerRoot cannot have a parent.', E_USER_WARNING);
        
    }//end setParent()
    
    
}//end LoggerRoot class
