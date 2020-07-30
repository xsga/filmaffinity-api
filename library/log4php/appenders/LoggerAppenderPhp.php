<?php
/**
 * LoggerAppenderPhp.
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
 * @subpackage Appenders
 * @link       http://logging.apache.org/log4php/docs/appenders/php.html Appender documentation
 */

namespace log4php\appenders;

use log4php\LoggerAppender;
use log4php\LoggerLoggingEvent;
use log4php\LoggerLevel;

/**
 * LoggerAppenderPhp.
 * 
 * LoggerAppenderPhp logs events by creating a PHP user-level message using 
 * the PHP's trigger_error()function.
 *
 * This appender has no configurable parameters.
 *
 * Levels are mapped as follows:
 * 
 * - <b>level < WARN</b> mapped to E_USER_NOTICE
 * - <b>WARN <= level < ERROR</b> mapped to E_USER_WARNING
 * - <b>level >= ERROR</b> mapped to E_USER_ERROR  
 */
class LoggerAppenderPhp extends LoggerAppender
{

    
    /**
     * Append.
     * 
     * @param LoggerLoggingEvent $event Event.
     * 
     * @return void
     * 
     * @access public
     */
    public function append(LoggerLoggingEvent $event)
    {
        $level = $event->getLevel();
        
        if ($level->isGreaterOrEqual(LoggerLevel::getLevelError()) === true) {
            trigger_error($this->layout->format($event), E_USER_ERROR);
        } elseif ($level->isGreaterOrEqual(LoggerLevel::getLevelWarn()) === true) {
            trigger_error($this->layout->format($event), E_USER_WARNING);
        } else {
            trigger_error($this->layout->format($event), E_USER_NOTICE);
        }//end if
        
    }//end append()
    
    
}//end LoggerAppenderPhp class
