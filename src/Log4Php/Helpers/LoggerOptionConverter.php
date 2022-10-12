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
 * PHP Version 8
 *
 * @package    Log4Php
 * @subpackage Helpers
 */

/**
 * Namespace.
 */
namespace Log4Php\Helpers;

/**
 * Import dependencies.
 */
use Log4Php\LoggerException;
use Log4Php\LoggerLevel;

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
    private static $trueValues = array('1', 'true', 'yes', 'on');

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
    private static $falseValues = array('0', 'false', 'no', 'off', '');

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
    public static function getSystemProperty(string $key, string $def): string
    {
        if (defined($key)) {
            return (string)constant($key);
        }//end if

        if (isset($_SERVER[$key])) {
            return (string)$_SERVER[$key];
        }//end if

        if (isset($_ENV[$key])) {
            return (string)$_ENV[$key];
        }//end if

        return $def;
    }

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
    public static function toBooleanEx(mixed $value): bool
    {
        if (isset($value)) {
            if (is_bool($value)) {
                return $value;
            }//end if

            $value = strtolower(trim($value));

            if (in_array($value, static::$trueValues)) {
                return true;
            }//end if

            if (in_array($value, static::$falseValues)) {
                return false;
            }//end if
        }//end if

        throw new LoggerException('Given value [' . var_export($value, true) . '] cannot be converted to boolean.');
    }

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
    public static function toIntegerEx(mixed $value): int
    {
        if (is_integer($value)) {
            return $value;
        }//end if

        if (is_numeric($value) && ($value === (int)$value)) {
            return (int)$value;
        }//end if

        throw new LoggerException('Given value [' . var_export($value, true) . '] cannot be converted to integer.');
    }

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
    public static function toPositiveIntegerEx(mixed $value): int
    {
        if (is_integer($value) && ($value > 0)) {
            return $value;
        }//end if

        if (is_numeric($value) && ($value === (int)$value) && ($value > 0)) {
            return $value;
        }//end if

        $log = 'Given value [' . var_export($value, true) . '] cannot be converted to a positive integer.';

        throw new LoggerException($log);
    }

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
    public static function toLevelEx(mixed $value): LoggerLevel
    {
        if ($value instanceof LoggerLevel) {
            return $value;
        }//end if

        $level = LoggerLevel::toLevel($value);

        if ($level === null) {
            $log = 'Given value [' . var_export($value, true) . '] cannot be converted to a logger level.';
            throw new LoggerException($log);
        }//end if

        return $level;
    }

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
     * @return integer
     *
     * @throws LoggerException
     *
     * @access public
     */
    public static function toFileSizeEx(mixed $value): int
    {
        if (empty($value)) {
            throw new LoggerException('Empty value cannot be converted to a file size.');
        }//end if

        if (is_numeric($value)) {
            return (int)$value;
        }//end if

        if (!is_string($value)) {
            $msg = 'Given value [' . var_export($value, true) . '] cannot be converted to a file size.';
            throw new LoggerException($msg);
        }//end if

        $str   = strtoupper(trim($value));
        $count = preg_match('/^([0-9.]+)(KB|MB|GB)?$/', $str, $matches);

        if ($count > 0) {
            $size = (int)$matches[1];
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

            return (int)$size;
        }//end if

        throw new LoggerException('Given value [' . $value . '] cannot be converted to a file size.');
    }

    /**
     * Converts a value to string, or throws an exception if not possible.
     *
     * Objects can be converted to string if they implement the magic __toString() method.
     *
     * @param mixed $value Value.
     *
     * @return string
     *
     * @throws LoggerException
     *
     * @access public
     */
    public static function toStringEx(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }//end if

        if (is_numeric($value)) {
            return (string)$value;
        }//end if

        if (is_object($value) && method_exists($value, '__toString')) {
            return (string)$value;
        }//end if

        throw new LoggerException('Given value [' . var_export($value, true) . '] cannot be converted to string.');
    }

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
    public static function substConstants(string $string): string
    {
        preg_match_all('/\${([^}]+)}/', $string, $matches);

        $replacement = '';

        foreach ($matches[1] as $key => $match) {
            $match  = trim($match);
            $search = $matches[0][$key];

            if (defined($match)) {
                $replacement = constant($match);
            } else {
                $replacement = '';
            }//end if

            $string = str_replace($search, $replacement, $string);
        }//end foreach

        return $string;
    }
}
