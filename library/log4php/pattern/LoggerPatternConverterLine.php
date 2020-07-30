<?php
/**
 * LoggerPatternConverterLine.
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
 * @package    Log4php
 * @subpackage Pattern
 */

namespace log4php\pattern;

use log4php\LoggerLoggingEvent;

/**
 * Returns the line number within the file from which the logging request was issued. 
 */
class LoggerPatternConverterLine extends LoggerPatternConverter
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
    public function convert(LoggerLoggingEvent $event)
    {
        
        return $event->getLocationInformation()->getLineNumber();
        
    }//end convert()
    
    
}//end LoggerPatternConverterLine class
