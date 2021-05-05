<?php
/**
 * LoggerFilterStringMatch.
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

/**
 * This is a very simple filter based on string matching.
 */
class LoggerFilterStringMatch extends LoggerFilter
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
     * String to match.
     * 
     * @var string
     * 
     * @access protected
     */
    protected $stringToMatch;
    
    
    /**
     * Set accept on match.
     * 
     * @param mixed $acceptOnMatch A boolean or a string ('true' or 'false').
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
     * Set string to much.
     * 
     * @param string $string The string to match.
     * 
     * @return void
     * 
     * @access public
     */
    public function setStringToMatch($string) : void
    {
        $this->setString('stringToMatch', $string);
        
    }//end setStringToMatch()
    
    
    /**
     * Decide. Return a LOGGER_FILTER_NEUTRAL is there is no string match.
     * 
     * @param LoggerLoggingEvent $event Event.
     * 
     * @return integer
     * 
     * @access public
     */
    public function decide(LoggerLoggingEvent $event) : int
    {
        $msg = $event->getRenderedMessage();
        
        if (($msg === null) || ($this->stringToMatch === null)) {
            return LoggerFilter::NEUTRAL;
            
        }//end if
        
        if (strpos($msg, $this->stringToMatch) !== false) {
            
            if ($this->acceptOnMatch) {
                return LoggerFilter::ACCEPT;
                
            } else {
                return LoggerFilter::DENY;
                
            }//end if
            
        }//end if
        
        return LoggerFilter::NEUTRAL;
        
    }//end decide()
    
    
}//end LoggerFilterStringMatch class
