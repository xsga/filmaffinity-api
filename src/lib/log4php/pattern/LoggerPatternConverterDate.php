<?php
/**
 * LoggerPatternConverterDate.
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
 * Returns the date/time of the logging request.
 * 
 * Option: the datetime format, as used by the date() function. If the option is not given, the default format 'c'
 * will be used.
 * 
 * There are several "special" values which can be given for this option: 'ISO8601', 'ABSOLUTE' and 'DATE'.
 */
class LoggerPatternConverterDate extends LoggerPatternConverter
{

    /**
     * Date format ISO8601.
     * 
     * @var string
     * 
     * @access public
     */
    const DATE_FORMAT_ISO8601 = 'c';
    
    /**
    * Date format absolute.
    * 
    * @var string
    * 
    * @access public
    */
    const DATE_FORMAT_ABSOLUTE = 'H:i:s';
    
    /**
     * Date format date.
     * 
     * @var string
     * 
     * @access public
     */
    const DATE_FORMAT_DATE = 'd M Y H:i:s.u';
    
    /**
     * Format.
     * 
     * @var string
     * 
     * @access private
     */
    private $format = self::DATE_FORMAT_ISO8601;
    
    /**
     * Specials.
     * 
     * @var array
     * 
     * @access private
     */
    private $specials = array(
                         'ISO8601'  => self::DATE_FORMAT_ISO8601,
                         'ABSOLUTE' => self::DATE_FORMAT_ABSOLUTE,
                         'DATE'     => self::DATE_FORMAT_DATE,
                        );
    
    /**
     * Use locale date.
     * 
     * @var boolean
     * 
     * @access private
     */
    private $useLocalDate = false;
    
    
    /**
     * Activate options.
     * 
     * @return void
     * 
     * @access public
     */
    public function activateOptions() : void
    {
        // Parse the option (date format).
        if (!empty($this->option)) {
            if (isset($this->specials[$this->option])) {
                $this->format = $this->specials[$this->option];
            } else {
                $this->format = $this->option;
            }//end if
        }//end if
        
        // Check whether the pattern contains milliseconds (u).
        if (preg_match('/(?<!\\\\)u/', $this->format) === 0) {
            $this->useLocalDate = true;
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
        if ($this->useLocalDate) {
            $out = $this->date($this->format, $event->getTimeStamp());
        } else {
            $out = date($this->format, $event->getTimeStamp());
        }//end if
        
        return $out;
        
    }//end convert()
    
    
    /**
     * Date function.
     * 
     * Currently, PHP date() function always returns zeros for milliseconds (u) on Windows. This is a replacement
     * function for date() which correctly displays milliseconds on all platforms. 
     * 
     * It is slower than PHP date() so it should only be used if necessary.
     * 
     * @param string $format     Format.
     * @param string $utimestamp Timestamp.
     * 
     * @return string
     * 
     * @access private
     */
    private function date($format, $utimestamp) : string
    {
        $timestamp = floor($utimestamp);
        $ms        = floor(($utimestamp - $timestamp) * 1000);
        $ms        = str_pad($ms, 3, '0', STR_PAD_LEFT);
        
        return date(preg_replace('`(?<!\\\\)u`', $ms, $format), $timestamp);
        
    }//end date()
    
    
}//end LoggerPatternConverterDate class
