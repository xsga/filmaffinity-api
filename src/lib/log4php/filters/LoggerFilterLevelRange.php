<?php
/**
 * LoggerFilterLevelRange.
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
namespace log4php\filters;

/**
 * Import dependencies.
 */
use log4php\LoggerFilter;
use log4php\LoggerLoggingEvent;
use log4php\LoggerLevel;

/**
 * LoggerFilterLevelRange.
 * 
 * This is a very simple filter based on level matching, which can be used to reject messages with priorities 
 * outside a certain range.
 */
class LoggerFilterLevelRange extends LoggerFilter
{
    
    /**
     * Accept on match.
     * 
     * @var boolean
     * 
     * @access protected
     */
    protected $acceptOnMatch = true;
    
    /**
     * Min level.
     * 
     * @var LoggerLevel
     * 
     * @access protected
     */
    protected $levelMin;
    
    /**
     * Max level.
     * 
     * @var LoggerLevel
     * 
     * @access protected
     */
    protected $levelMax;
    
    
    /**
     * Set accept on match.
     * 
     * @param boolean $acceptOnMatch Accept on match.
     * 
     * @return void
     * 
     * @access public
     */
    public function setAcceptOnMatch($acceptOnMatch) : void
    {
        $this->setBoolean('acceptOnMatch', $acceptOnMatch);
        
    }//end setAcceptOnMatch()
    
    
    /**
     * Set min level.
     * 
     * @param string $level The level min to match.
     * 
     * @return void
     * 
     * @access public
     */
    public function setLevelMin($level) : void
    {
        $this->setLevel('levelMin', $level);
        
    }//end setLevelMin()
    
    
    /**
     * Set max level.
     * 
     * @param string $level The level max to match.
     * 
     * @return void
     * 
     * @access public
     */
    public function setLevelMax($level) : void
    {
        $this->setLevel('levelMax', $level);
        
    }//end setLevelMax()
    
    
    /**
     * Return the decision of this filter.
     *
     * @param LoggerLoggingEvent $event Event.
     * 
     * @return integer
     * 
     * @access public
     */
    public function decide(LoggerLoggingEvent $event) : int
    {
        $level = $event->getLevel();
        
        if (($this->levelMin !== null) && !$level->isGreaterOrEqual($this->levelMin)) {

            // Level of event is less than minimum.
            return LoggerFilter::DENY;
            
        }//end if
        
        if (($this->levelMax !== null) && ($level->toInt() > $this->levelMax->toInt())) {
            
            // Level of event is greater than maximum.
            return LoggerFilter::DENY;
            
        }//end if
        
        if ($this->acceptOnMatch) {
            
            // This filter set up to bypass later filters and always return accept if level in range.
            return LoggerFilter::ACCEPT;
            
        } else {
            
            // Event is ok for this filter; allow later filters to have a look.
            return LoggerFilter::NEUTRAL;
            
        }//end if
        
    }//end decide()
    
    
}//end LoggerFilterLevelRange class
