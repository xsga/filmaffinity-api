<?php
/**
 * LoggerConfigurable.
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
 * @package Log4php
 */

/**
 * Namespace.
 */
namespace log4php;

/**
 * Import dependencies.
 */
use log4php\helpers\LoggerOptionConverter;

/**
 * A base class from which all classes which have configurable properties are extended.
 */
abstract class LoggerConfigurable
{
    
    
    /**
     * Setter function for boolean type.
     * 
     * @param string $property Property.
     * @param string $value    Value.
     * 
     * @return void
     * 
     * @access protected
     */
    protected function setBoolean($property, $value) : void
    {
        try {
            
            $this->$property = LoggerOptionConverter::toBooleanEx($value);
            
        } catch (\Exception $ex) {
            
            $value = var_export($value, true);
            
            $msg  = 'Invalid value given for \''.$property.'\' property: ['.$value.']. ';
            $msg .= 'Expected a boolean value. Property not changed.';
            
            $this->warn($msg);

        }//end try
        
    }//end setBoolean()
    
    
    /**
     * Setter function for integer type.
     * 
     * @param string $property Property.
     * @param string $value    Value.
     * 
     * @return void
     * 
     * @access protected
     */
    protected function setInteger($property, $value) : void
    {
        try {
            
            $this->$property = LoggerOptionConverter::toIntegerEx($value);
            
        } catch (\Exception $ex) {
            
            $value = var_export($value, true);
            
            $msg  = 'Invalid value given for \''.$property.'\' property: ['.$value.']. ';
            $msg .= 'Expected an integer. Property not changed.';
            
            $this->warn($msg);
            
        }//end try
        
    }//end setInteger()
    
    
    /**
     * Setter function for LoggerLevel values.
     * 
     * @param string $property Property.
     * @param string $value    Value.
     * 
     * @return void
     * 
     * @access protected
     */
    protected function setLevel($property, $value) : void
    {
        try {
            
            $this->$property = LoggerOptionConverter::toLevelEx($value);
            
        } catch (\Exception $ex) {
            
            $value = var_export($value, true);
            
            $msg  = 'Invalid value given for \''.$property.'\' property: ['.$value.']. ';
            $msg .= 'Expected a level value. Property not changed.';
            
            $this->warn($msg);
            
        }//end try
        
    }//end setLevel()
    
    
    /**
     * Setter function for integer type.
     * 
     * @param string $property Property.
     * @param string $value    Value.
     * 
     * @return void
     * 
     * @access protected
     */
    protected function setPositiveInteger($property, $value) : void
    {
        try {
            
            $this->$property = LoggerOptionConverter::toPositiveIntegerEx($value);
            
        } catch (\Exception $ex) {
            
            $value = var_export($value, true);
            
            $msg  = 'Invalid value given for \''.$property.'\' property: ['.$value.']. ';
            $msg .= 'Expected a positive integer. Property not changed.';
            
            $this->warn($msg);
            
        }//end try
        
    }//end setPositiveInteger()
    
    
    /**
     * Setter for file size.
     * 
     * @param string $property Property.
     * @param string $value    Value.
     * 
     * @return void
     * 
     * @access protected
     */
    protected function setFileSize($property, $value) : void
    {
        try {
            
            $this->$property = LoggerOptionConverter::toFileSizeEx($value);
            
        } catch (\Exception $ex) {
            
            $value = var_export($value, true);
            
            $msg  = 'Invalid value given for \''.$property.'\' property: ['.$value.']. ';
            $msg .= 'Expected a file size value.  Property not changed.';
            
            $this->warn($msg);
            
        }//end try
        
    }//end setFileSize()
    
    
    /**
     * Setter function for numeric type.
     * 
     * @param string $property Property.
     * @param string $value    Value.
     * 
     * @return void
     * 
     * @access protected
     */
    protected function setNumeric($property, $value) : void
    {
        try {
            
            $this->$property = LoggerOptionConverter::toNumericEx($value);
            
        } catch (\Exception $ex) {
            
            $value = var_export($value, true);
            
            $msg  = 'Invalid value given for \''.$property.'\' property: ['.$value.']. ';
            $msg .= 'Expected a number. Property not changed.';
            
            $this->warn($msg);
            
        }//end try
        
    }//end setNumeric()
    
    
    /**
     * Setter function for string type.
     * 
     * @param string  $property Property.
     * @param string  $value    Value.
     * @param boolean $nullable Nullable.
     * 
     * @return void
     * 
     * @access protected
     */
    protected function setString($property, $value, $nullable = false) : void
    {
        if ($value === null) {
            
            if ($nullable) {
                
                $this->$property = null;
                
            } else {
                
                $msg = 'Null value given for \''.$property.'\' property. Expected a string. Property not changed.';
                $this->warn($msg);
                
            }//end if

        } else {
            
            try {
                
                $value = LoggerOptionConverter::toStringEx($value);
                $this->$property = LoggerOptionConverter::substConstants($value);
                
            } catch (\Exception $ex) {
                
                $value = var_export($value, true);
                
                $msg  = 'Invalid value given for \''.$property.'\' property: ['.$value.']. ';
                $msg .= 'Expected a string. Property not changed.';
                
                $this->warn($msg);
                
            }//end try
            
        }//end if
        
    }//end setString()
    
    
    /**
     * Triggers a warning.
     * 
     * @param string $message Message.
     * 
     * @return void
     * 
     * @access protected
     */
    protected function warn($message) : void
    {
        $class = get_class($this);
        trigger_error('log4php: '.$class.': '.$message, E_USER_WARNING);
        
    }//end warn()
    
    
}//end LoggerConfigurable class
