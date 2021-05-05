<?php
/**
 * LoggerLayoutSerialized.
 * 
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements.  See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * PHP Version 8
 *
 * @package    Log4php
 * @subpackage Layouts
 */

/**
 * Namespace.
 */
namespace log4php\layouts;

/**
 * Import dependencies.
 */
use log4php\LoggerLayout;
use log4php\LoggerLoggingEvent;

/**
 * Layout which formats the events using PHP's serialize() function.
 * 
 * Available options:
 * - locationInfo - If set to true, the event's location information will also
 *                  be serialized (slow, defaults to false).
 */
class LoggerLayoutSerialized extends LoggerLayout
{
    
    /**
     * Whether to include the event's location information (slow).
     * 
     * @var boolean
     * 
     * @access protected
     */
    protected $locationInfo = false;
    
    
    /**
     * Sets the location information flag.
     * 
     * @param boolean $value Value.
     * 
     * @return void
     * 
     * @access public
     */
    public function setLocationInfo($value) : void
    {
        $this->setBoolean('locationInfo', $value);
        
    }//end setLocationInfo()
    
    
    /**
     * Returns the location information flag.
     * 
     * @return boolean
     * 
     * @access public
     */
    public function getLocationInfo() : bool
    {
        return $this->locationInfo;
        
    }//end getLocationInfo()
    
    
    /**
     * Format.
     * 
     * @param LoggerLoggingEvent $event Event.
     * 
     * @return string
     * 
     * @access public
     */
    public function format(LoggerLoggingEvent $event) : string
    {
        // If required, initialize the location data.
        if ($this->locationInfo) {
            $event->getLocationInformation();
        }//end if
        
        return serialize($event).PHP_EOL;
        
    }//end format()


}//end LoggerLayoutSerialized class
