<?php
/**
 * LoggerOptionConverter.
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
 * @subpackage Helpers
 */

namespace log4php\helpers;

use log4php\LoggerException;
use log4php\LoggerLevel;

/**
 * A convenience class to convert property values to specific types.
 */
class LoggerOptionConverter
{
    
    /**
     * String values which are converted to boolean true.
     * 
     * @var array
     * 
     * @access private
     */
    private static $trueValues = array(
                                  '1',
                                  'true',
                                  'yes',
                                  'on',
                                 );
    
    /**
     * String values which are converted to boolean false.
     * 
     * Note that an empty string must convert to false, because parse_ini_file() which is used for parsing
     * configuration converts the value _false_ to an empty string.
     * 
     * @var array
     * 
     * @access private
     */
    private static $falseValues = array(
                                   '0',
                                   'false',
                                   'no',
                                   'off',
                                   '',
                                  );
    
    
    /**
     * Read a predefined var.
     *
     * It returns a value referenced by <var>$key</var> using this search criteria:
     * - if <var>$key</var> is a constant then return it. Else
     * - if <var>$key</var> is set in <var>$_ENV</var> then return it. Else
     * - return <var>$def</var>. 
     *
     * @param string $key The key to search for.
     * @param string $def The default value to return.
     * 
     * @return string String value of the system property, or the default value if there is no property with that key.
     * 
     * @access public
     */
    public static function getSystemProperty($key, $def)
    {
        
        if (defined($key) === true) {
            $out = (string)constant($key);
        } elseif (isset($_SERVER[$key]) === true) {
            $out = (string)$_SERVER[$key];
        } elseif (isset($_ENV[$key]) === true) {
            $out = (string)$_ENV[$key];
        } else {
            $out = $def;
        }//end if
        
        return $out;
        
    }//end getSystemProperty()
    

    /**
     * Converts $value to boolean, or throws an exception if not possible.
     * 
     * @param mixed $value Value.
     * 
     * @return boolean
     * 
     * @throws LoggerException
     * 
     * @access public
     */
    public static function toBooleanEx($value)
    {
        if (isset($value) === true) {
            
            if (is_bool($value) === true) {
                return $value;
            }//end if
            
            $value = strtolower(trim($value));
            
            if (in_array($value, static::$trueValues) === true) {
                return true;
            }//end if
            
            if (in_array($value, static::$falseValues) === true) {
                return false;
            }//end if
            
        }//end if
        
        throw new LoggerException('Given value ['.var_export($value, true).'] cannot be converted to boolean.');
        
    }//end toBooleanEx()
    
    
    /**
     * Converts $value to integer, or throws an exception if not possible. Floats cannot be converted to integer.
     * 
     * @param mixed $value Value.
     * 
     * @return integer
     * 
     * @throws LoggerException
     * 
     * @access public
     */
    public static function toIntegerEx($value)
    {
        if (is_integer($value) === true) {
            return $value;
        }//end if
        
        if ((is_numeric($value) === true) && ($value === (integer)$value)) {
            return (integer)$value;
        }//end if
    
        throw new LoggerException('Given value ['.var_export($value, true).'] cannot be converted to integer.');
        
    }//end toIntegerEx()
    
    
    /**
     * Converts $value to integer, or throws an exception if not possible. Floats cannot be converted to integer.
     * 
     * @param mixed $value Value.
     * 
     * @return integer
     * 
     * @throws LoggerException
     * 
     * @access public
     */
    public static function toPositiveIntegerEx($value)
    {
        if ((is_integer($value) === true) && ($value > 0)) {
            return $value;
        }//end if
        
        if ((is_numeric($value) === true) && ($value === (integer)$value) && ($value > 0)) {
            return (integer)$value;
        }//end if
    
        $log = 'Given value ['.var_export($value, true).'] cannot be converted to a positive integer.';
        
        throw new LoggerException($log);
        
    }//end toPositiveIntegerEx()
    

    /**
     * Converts the value to a level. Throws an exception if not possible.
     * 
     * @param mixed $value Value.
     * 
     * @return LoggerLevel
     * 
     * @throws LoggerException
     * 
     * @access public
     */
    public static function toLevelEx($value)
    {
        if ($value instanceof LoggerLevel) {
            return $value;
        }//end if
        
        $level = LoggerLevel::toLevel($value);
        
        if ($level === null) {
            $log = 'Given value ['.var_export($value, true).'] cannot be converted to a logger level.';
            throw new LoggerException($log);
        }//end if
        
        return $level;
        
    }//end toLevelEx()
    

    /**
     * Converts a value to a valid file size (integer).
     * 
     * Supports 'KB', 'MB' and 'GB' suffixes, where KB = 1024 B etc. 
     *
     * The final value will be rounded to the nearest integer.
     *
     * Examples:
     * - '100' => 100
     * - '100.12' => 100
     * - '100KB' => 102400
     * - '1.5MB' => 1572864
     * 
     * @param mixed $value File size (optionally with suffix).
     * 
     * @return integer Parsed file size.
     * 
     * @throws LoggerException
     * 
     * @access public
     */
    public static function toFileSizeEx($value)
    {
        
        if (empty($value) === true) {
            throw new LoggerException('Empty value cannot be converted to a file size.');
        }//end if
        
        if (is_numeric($value) === true) {
            return (integer)$value;
        }//end if
        
        if (is_string($value) === false) {
            throw new LoggerException('Given value ['.var_export($value, true).'] cannot be converted to a file size.');
        }//end if
        
        $str   = strtoupper(trim($value));
        $count = preg_match('/^([0-9.]+)(KB|MB|GB)?$/', $str, $matches);
        
        if ($count > 0) {
            
            $size = $matches[1];
            $unit = $matches[2];
            
            switch ($unit) {
                case 'KB':
                    $size *= pow(1024, 1);
                    break;
                    
                case 'MB':
                    $size *= pow(1024, 2);
                    break;
                    
                case 'GB':
                    $size *= pow(1024, 3);
                    break;
                
                default:
                    break;
            }//end switch
            
            return (integer)$size;
            
        }//end if
        
        throw new LoggerException('Given value ['.$value.'] cannot be converted to a file size.');
        
    }//end toFileSizeEx()
    

    /**
     * Converts a value to string, or throws an exception if not possible. 
     * 
     * Objects can be converted to string if they implement the magic __toString() method.
     * 
     * @param string $value Value.
     * 
     * @return string
     * 
     * @throws LoggerException
     * 
     * @access public
     */
    public static function toStringEx($value) 
    {
        if (is_string($value) === true) {
            return $value;
        }//end if
        
        if (is_numeric($value) === true) {
            return (string)$value;
        }//end if
        
        if ((is_object($value) === true) && (method_exists($value, '__toString') === true)) {
            return (string)$value;
        }//end if
    
        throw new LoggerException('Given value ['.var_export($value, true).'] cannot be converted to string.');
        
    }//end toStringEx()
    
    
    /**
     * Performs value substitution for string options.
     * 
     * An option can contain PHP constants delimited by '${' and '}'.
     * 
     * E.g. for input string "some ${FOO} value", the method will attempt 
     * to substitute ${FOO} with the value of constant FOO if it exists.
     * 
     * Therefore, if FOO is a constant, and it has value "bar", the resulting 
     * string will be "some bar value". 
     * 
     * If the constant is not defined, it will be replaced by an empty string, 
     * and the resulting string will be "some  value". 
     * 
     * @param string $string String on which to perform substitution.
     * 
     * @return string
     * 
     * @access public
     */
    public static function substConstants($string)
    {
        preg_match_all('/\${([^}]+)}/', $string, $matches);
        
        foreach ($matches[1] as $key => $match) {
            
            $match  = trim($match);
            $search = $matches[0][$key];
            $string = str_replace($search, $replacement, $string);
            
            if (defined($match) === true) {
                $replacement = constant($match);
            } else {
                $replacement = '';
            }//end if
            
        }//end foreach
        
        return $string;
        
    }//end substConstants()
    
    
}//end LoggerOptionConverter class
