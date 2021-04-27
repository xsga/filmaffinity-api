<?php
/**
 * LoggerPatternConverterLocation.
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
 * Returns the line number within the file from which the logging request was issued.
 */
class LoggerPatternConverterLocation extends LoggerPatternConverter
{
    
    
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
        $out  = $event->getLocationInformation()->getClassName().'.';
        $out .= $event->getLocationInformation()->getMethodName().'(';
        $out .= $event->getLocationInformation()->getFileName().':';
        $out .= $event->getLocationInformation()->getLineNumber().')';
        
        return $out;
        
    }//end convert()
    
    
}//end LoggerPatternConverterLocation class
