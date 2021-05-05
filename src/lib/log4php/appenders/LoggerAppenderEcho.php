<?php
/**
 * LoggerAppenderEcho.
 * 
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements. See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
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
 * @subpackage Appenders
 * @link       http://logging.apache.org/log4php/docs/appenders/echo.html Appender documentation
 */

/**
 * Namespace.
 */
namespace log4php\appenders;

/**
 * Import dependencies.
 */
use log4php\LoggerAppender;
use log4php\LoggerLoggingEvent;

/**
 * LoggerAppenderEcho uses the PHP echo() function to output events. 
 * 
 * This appender uses a layout.
 * 
 * ## Configurable parameters: ##
 * 
 * - **htmlLineBreaks** - If set to true, a <br /> element will be inserted 
 *     before each line break in the logged message. Default is false.
 */
class LoggerAppenderEcho extends LoggerAppender
{
    
    /**
     * Used to mark first append. Set to false after first append.
     * 
     * @var boolean
     * 
     * @access protected
     */
    protected $firstAppend = true;
    
    /**
     * If set to true, a <br /> element will be inserted before each line break in the logged message.
     * 
     * @var boolean
     * 
     * @access protected
     */
    protected $htmlLineBreaks = false;
    
    
    /**
     * Close.
     * 
     * @return void
     * 
     * @access public
     * @see    LoggerAppender::close()
     */
    public function close() : void
    {
        
        if (!$this->closed && !$this->firstAppend) {
            echo $this->layout->getFooter();
        }//end if
        
        $this->closed = true;
        
    }//end close()
    

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
    public function append(LoggerLoggingEvent $event) : void
    {
        if ($this->layout !== null) {
            
            if ($this->firstAppend) {
                
                echo $this->layout->getHeader();
                $this->firstAppend = false;
                
            }//end if
            
            $text = $this->layout->format($event);
            
            if ($this->htmlLineBreaks) {
                
                $text = nl2br($text);
                
            }//end if
            
            echo $text;
            
        }//end if
        
    }//end append()
    
    
    /**
     * Sets the 'htmlLineBreaks' parameter.
     * 
     * @param boolean $value Value.
     * 
     * @return void
     * 
     * @access public
     */
    public function setHtmlLineBreaks($value) : void
    {
        $this->setBoolean('htmlLineBreaks', $value);
        
    }//end setHtmlLineBreaks()
    
    
    /**
     * Returns the 'htmlLineBreaks' parameter.
     * 
     * @return boolean
     * 
     * @access public
     */
    public function getHtmlLineBreaks() : bool
    {
        return $this->htmlLineBreaks;
        
    }//end getHtmlLineBreaks()


}//end LoggerAppenderEcho class
