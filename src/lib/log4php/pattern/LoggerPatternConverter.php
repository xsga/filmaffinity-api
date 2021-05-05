<?php
/**
 * LoggerPatternConverter.
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
 * @subpackage Helpers
 */

/**
 * Namespace.
 */
namespace log4php\pattern;

/**
 * Import dependencies.
 */
use log4php\helpers\LoggerFormattingInfo;
use log4php\LoggerLoggingEvent;

/**
 * LoggerPatternConverter is an abstract class that provides the formatting functionality that derived classes need.
 * 
 * <p>Conversion specifiers in a conversion patterns are parsed to individual PatternConverters. Each of which is
 * responsible for converting a logging event in a converter specific manner.</p>
 */
abstract class LoggerPatternConverter
{
    
    /**
     * Next converter in the converter chain.
     * 
     * @var LoggerPatternConverter
     * 
     * @access public
     */
    public $next = null;
    
    /**
     * Formatting information, parsed from pattern modifiers.
     * 
     * @var LoggerFormattingInfo
     * 
     * @access protected
     */
    protected $formattingInfo;
    
    /**
     * Converter-specific formatting options.
     * 
     * @var string
     * 
     * @access protected
     */
    protected $option;

    
    /**
     * Constructor.
     * 
     * @param LoggerFormattingInfo $formattingInfo Formatting info.
     * @param string               $option         Option.
     * 
     * @access public
     */
    public function __construct(LoggerFormattingInfo $formattingInfo = null, $option = null)
    {  
        $this->formattingInfo = $formattingInfo;
        $this->option         = $option;
        
        $this->activateOptions();
        
    }//end __construct()
    
    
    /**
     * Called in constructor. Converters which need to process the options can override this method.
     * 
     * @return void
     * 
     * @access public
     */
    public function activateOptions() : void
    {
        
    }//end activateOptions()
    
  
    /**
     * Converts the logging event to the desired format. Derived pattern converters must implement this method.
     *
     * @param LoggerLoggingEvent $event Event.
     * 
     * @return mixed
     * 
     * @access public
     */
    abstract public function convert(LoggerLoggingEvent $event) : mixed;
    

    /**
     * Converts the event and formats it according to setting in the formatting information object.
     *
     * @param string             &$sbuf String buffer to write to.
     * @param LoggerLoggingEvent $event Event to be formatted.
     * 
     * @return void
     * 
     * @access public
     */
    public function format(&$sbuf, LoggerLoggingEvent $event) : void
    {
        $string = $this->convert($event);
        
        if (!isset($this->formattingInfo)) {
            $sbuf .= $string;
            return;    
        }//end if
        
        $fi = $this->formattingInfo;
        
        // Empty string.
        if (($string === '') || is_null($string)) {
            if ($fi->min > 0) {
                $sbuf .= str_repeat(' ', $fi->min);
            }//end if
            return;
        }//end if
        
        $len = strlen($string);
    
        // Trim the string if needed.
        if ($len > $fi->max) {
            if ($fi->trimLeft) {
                $sbuf .= substr($string, ($len - $fi->max), $fi->max);
            } else {
                $sbuf .= substr($string, 0, $fi->max);
            }//end if
        } elseif ($len < $fi->min) {
            // Add padding if needed.
            if ($fi->padLeft) {
                $sbuf .= str_repeat(' ', ($fi->min - $len));
                $sbuf .= $string;
            } else {
                $sbuf .= $string;
                $sbuf .= str_repeat(' ', ($fi->min - $len));
            }//end if
        } else {
            // No action needed.
            $sbuf .= $string;
        }//end if
        
    }//end format()


}//end LoggerPatternConverter class
