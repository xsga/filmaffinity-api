<?php
/**
 * LoggerPatternConverterMDC.
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
 * @package    Log4php
 * @subpackage Pattern
 */

/**
 * Namespace.
 */
namespace log4php\pattern;

/**
 * Import dependencies.
 */
use log4php\LoggerLoggingEvent;

/**
 * Returns the Mapped Diagnostic Context value corresponding to the given key.
 * 
 * Options:
 *  [0] the MDC key
 */
class LoggerPatternConverterMDC extends LoggerPatternConverter
{

    /**
     * Key.
     * 
     * @var string
     * 
     * @access private
     */
    private $key;
    

    /**
     * Activate options.
     * 
     * @return void
     * 
     * @access public
     */
    public function activateOptions() : void
    {
        if (isset($this->option) && ($this->option !== '')) {
            $this->key = $this->option;
        }//end if
        
    }//end activateOptions()
    
    
    /**
     * Convert.
     * 
     * @param LoggerLoggingEvent $event Event.
     * 
     * @return string
     * 
     * @access public
     */
    public function convert(LoggerLoggingEvent $event) : string
    {
        if (isset($this->key)) {
            
            $out = $event->getMDC($this->key);
            
        } else {
            
            $buff = array();
            $map  = $event->getMDCMap();
            
            foreach ($map as $key => $value) {
                $buff[] = $key.'='.$value;
            }//end foreach
            
            $out = implode(', ', $buff);
            
        }//end if
        
        return $out;
        
    }//end convert()
    
    
}//end LoggerPatternConverterMDC class
