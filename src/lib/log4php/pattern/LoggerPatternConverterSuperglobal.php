<?php
/**
 * LoggerPatternConverterSuperglobal.
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
 * Returns a value from a superglobal array corresponding to the given key.
 * 
 * Option: the key to look up within the superglobal array.
 * 
 * Also, it is possible that a superglobal variable is not populated by PHP because of the settings in the
 * variables-order ini directive. In this case the converter will return an empty value.
 */
abstract class LoggerPatternConverterSuperglobal extends LoggerPatternConverter
{

    /**
     * Name of the superglobal variable, to be defined by subclasses. For example: "_SERVER" or "_ENV".
     * 
     * @var string
     * 
     * @access protected
     */
    protected $name;
    
    /**
     * Value.
     * 
     * @var string
     * 
     * @access protected
     */
    protected $value = '';
    
    
    /**
     * Activate options.
     * 
     * @return void
     * 
     * @access public
     */
    public function activateOptions() : void
    {
        // Read the key from options array.
        if (isset($this->option) && ($this->option !== '')) {
            $key = $this->option;
        }//end if
    
        $GLOBALS[$this->name];
        
        // Check the given superglobal exists. It is possible that it is not initialized.
        if (!isset($GLOBALS[$this->name])) {
            $class = get_class($this);
            trigger_error('log4php: '.$class.': Cannot find superglobal variable $'.$this->name, E_USER_WARNING);
            return;
        }//end if
        
        $source = $GLOBALS[$this->name];
        
        // When the key is set, display the matching value.
        if (isset($key)) {
            if (isset($source[$key])) {
                $this->value = $source[$key];
                if (empty($this->value)) {
                    $this->value = 'empty';
                }//end if
            } else {
                $this->value = 'empty';
            }//end if
        } else {
            // When the key is not set, display all values.
            $values = array();
            foreach ($source as $key => $value) {
                $values[] = $key.'='.$value;
            }//end foreach
            $this->value = implode(', ', $values);
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
        return $this->value;
        
    }//end convert()
    
    
}//end LoggerPatternConverterSuperglobal class
