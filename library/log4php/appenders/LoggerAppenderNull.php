<?php
/**
 * LoggerAppenderNull.
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
 * @link       http://logging.apache.org/log4php/docs/appenders/null.html Appender documentation
 */

namespace log4php\appenders;

use log4php\LoggerAppender;
use log4php\LoggerLoggingEvent;

/**
 * A NullAppender merely exists, it never outputs a message to any device.    
 *
 * This appender has no configurable parameters.
 */
class LoggerAppenderNull extends LoggerAppender
{

    /**
     * This appender does not require a layout.
     * 
     * @var boolean
     */
    protected $requiresLayout = false;
    
    
    /**
     * Do nothing.
     * 
     * @param LoggerLoggingEvent $event Event.
     * 
     * @return void
     * 
     * @access public
     */
    public function append(LoggerLoggingEvent $event)
    {
        
        
    }//end append()
    
    
}//end LoggerAppenderNull class
