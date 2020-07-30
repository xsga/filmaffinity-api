<?php
/**
 * LoggerAppenderDailyFile.
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
 * PHP Version 5
 * 
 * @package    Log4php
 * @subpackage Appenders
 */

namespace log4php\appenders;

use log4php\LoggerLoggingEvent;

/**
 * An Appender that automatically creates a new logfile each day.
 *
 * The file is rolled over once a day. That means, for each day a new file 
 * is created. A formatted version of the date pattern is used as to create 
 * the file name using the {@link PHP_MANUAL#sprintf} function.
 *
 * This appender uses a layout.
 * 
 * ##Configurable parameters:##
 * 
 * - **datePattern** - Format for the date in the file path, follows formatting
 *     rules used by the PHP date() function. Default value: "Ymd".
 * - **file** - Path to the target file. Should contain a %s which gets 
 *     substituted by the date.
 * - **append** - If set to true, the appender will append to the file, 
 *     otherwise the file contents will be overwritten. Defaults to true.
 */
class LoggerAppenderDailyFile extends LoggerAppenderFile
{

    /**
     * The 'datePattern' parameter.
     * 
     * Determines how date will be formatted in file name.
     * 
     * @var string
     * 
     * @access protected
     */
    protected $datePattern = "Ymd";
    
    /**
     * Current date which was used when opening a file.
     * 
     * Used to determine if a rollover is needed when the date changes.
     * 
     * @var string
     * 
     * @access protected
     */
    protected $currentDate;
    

    /**
     * Additional validation for the date pattern.
     * 
     * @return void
     * 
     * @access public
     */
    public function activateOptions()
    {
        
        parent::activateOptions();
    
        if (empty($this->datePattern) === true) {
            
            $this->warn("Required parameter 'datePattern' not set. Closing appender.");
            $this->closed = true;
            
        }//end if
        
    }//end activateOptions()
    

    /**
     * Appends a logging event.
     * 
     * If the target file changes because of passage of time (e.g. at midnight) 
     * the current file is closed. A new file, with the new date, will be 
     * opened by the write() method.
     * 
     * @param LoggerLoggingEvent $event Event.
     * 
     * @return void
     * 
     * @access public
     */
    public function append(LoggerLoggingEvent $event)
    {
        
        $eventDate = $this->getDate($event->getTimestamp());
        
        // Initial setting of current date.
        if (isset($this->currentDate) === false) {

            $this->currentDate = $eventDate;
        
        } elseif ($this->currentDate !== $eventDate) {
            
            // Check if rollover is needed.
            $this->currentDate = $eventDate;
            
            // Close the file if it's open.
            if (is_resource($this->fp) === true) {
                
                $this->write($this->layout->getFooter());
                fclose($this->fp);
                
            }//end if
            
            $this->fp = null;
            
        }//end if
    
        parent::append($event);
        
    }//end append()
    
    
    /**
     * Renders the date using the configured datePattern.
     * 
     * @param string $timestamp Timestamp.
     * 
     * @return string
     * 
     * @access protected
     */
    protected function getDate($timestamp=null)
    {
        
        return date($this->datePattern, $timestamp);
        
    }//end getDate()
    
    
    /**
     * Determines target file. Replaces %s in file path with a date.
     * 
     * @return string
     * 
     * @access protected
     */
    protected function getTargetFile()
    {
        
        return str_replace('%s', $this->currentDate, $this->file);
        
    }//end getTargetFile()
    
    
    /**
     * Sets the 'datePattern' parameter.
     * 
     * @param string $datePattern Date pattern.
     * 
     * @return void
     * 
     * @access public
     */
    public function setDatePattern($datePattern)
    {
        
        $this->setString('datePattern', $datePattern);
        
    }//end setDatePattern()
    
    
    /**
     * Returns the 'datePattern' parameter.
     * 
     * @return string
     * 
     * @access public
     */
    public function getDatePattern()
    {
        
        return $this->datePattern;
        
    }//end getDatePattern()
    
    
}//end LoggerAppenderDailyFile class
