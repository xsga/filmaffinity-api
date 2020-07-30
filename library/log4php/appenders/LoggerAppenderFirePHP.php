<?php
/**
 * LoggerAppendeFirePHP.
 * 
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements.  See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License.  You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
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
 * @link       https://github.com/firephp/firephp FirePHP homepage.
 * @link       http://logging.apache.org/log4php/docs/appenders/firephp.html Appender documentation
 */

namespace log4php\appenders;

use log4php\LoggerAppender;
use log4php\LoggerLoggingEvent;
use log4php\LoggerLevel;

/**
 * Logs messages as HTTP headers using the FirePHP Insight API.
 * 
 * This appender requires the FirePHP server library version 1.0 or later.
 * 
 * ## Configurable parameters: ##
 * 
 * - **target** - (string) The target to which messages will be sent. Possible options are 
 *            'page' (default), 'request', 'package' and 'controller'. For more details,
 *            see FirePHP documentation.
 * 
 * This class was originally contributed by Bruce Ingalls (Bruce.Ingalls-at-gmail-dot-com).
 */
class LoggerAppenderFirePHP extends LoggerAppender
{
    
    /**
     * Instance of the Insight console class.
     * 
     * @var Insight_Plugin_Console
     * 
     * @access protected
     */
    protected $console;
    
    /**
     * The target for log messages. Possible values are: 'page' (default), 'request', 'package' and 'contoller'.
     * 
     * @var string
     * 
     * @access protected
     */
    protected $target = 'page';
    

    /**
     * Activate options.
     * 
     * @return void
     * 
     * @access public
     * @see    LoggerAppender::activateOptions()
     */
    public function activateOptions()
    {

        if (method_exists('FirePHP', 'to') === true) {
            
            $this->console = FirePHP::to($this->target)->console();
            $this->closed  = false;
            
        } else {
            
            $this->warn('FirePHP is not installed correctly. Closing appender.');
            
        }//end if
        
    }//end activateOptions()
    
    
    /**
     * Append.
     * 
     * @param LoggerLoggingEvent $event Event.
     * 
     * @return void
     * 
     * @access public
     * @see    LoggerAppender::append()
     */
    public function append(LoggerLoggingEvent $event)
    {

        $msg = $event->getMessage();
        
        // Skip formatting for objects and arrays which are handled by FirePHP.
        if ((is_array($msg) === false) && (is_object($msg) === false)) {
            
            $msg = $this->getLayout()->format($event);
            
        }//end if
        
        switch ($event->getLevel()->toInt()) {
            case LoggerLevel::TRACE:
            case LoggerLevel::DEBUG:
                $this->console->log($msg);
                break;
                
            case LoggerLevel::INFO:
                $this->console->info($msg);
                break;
                
            case LoggerLevel::WARN:
                $this->console->warn($msg);
                break;
                
            case LoggerLevel::ERROR:
            case LoggerLevel::FATAL:
                $this->console->error($msg);
                break;
                
            default:
                break;
        }//end switch
        
    }//end append()
    
    
    /**
     * Returns the target.
     * 
     * @return string
     * 
     * @access public
     */
    public function getTarget()
    {
        
        return $this->target;
        
    }//end getTarget()
    

    /**
     * Sets the target.
     * 
     * @param string $target Target.
     * 
     * @return void
     * 
     * @access public
     */
    public function setTarget($target)
    {

        $this->setString('target', $target);
        
    }//end setTarget()
    
    
}//end LoggerAppenderFirePHP class
