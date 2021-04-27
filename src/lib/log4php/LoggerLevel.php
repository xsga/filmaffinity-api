<?php
/**
 * LoggerLevel.
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
 * Defines the minimum set of levels recognized by the system, that is OFF, FATAL, ERROR, WARN, INFO, DEBUG and ALL.
 */
class LoggerLevel
{
    
    /**
     * Off.
     * 
     * @var integer
     * 
     * @access public
     */
    const OFF = 2147483647;
    
    /**
     * Fatal.
     * 
     * @var integer
     * 
     * @access public
     */
    const FATAL = 50000;
    
    /**
     * Error.
     * 
     * @var integer
     * 
     * @access public
     */
    const ERROR = 40000;
    
    /**
     * Warn.
     * 
     * @var integer
     * 
     * @access public
     */
    const WARN = 30000;
    
    /**
     * Info.
     * 
     * @var integer
     * 
     * @access public
     */
    const INFO = 20000;
    
    /**
     * Debug.
     * 
     * @var integer
     * 
     * @access public
     */
    const DEBUG = 10000;
    
    /**
     * Trace.
     * 
     * @var integer
     * 
     * @access public
     */
    const TRACE = 5000;
    
    /**
     * All.
     * 
     * @var integer
     * 
     * @access public
     */
    const ALL = -2147483647;
    
    /**
     * Integer level value.
     * 
     * @var integer
     * 
     * @access private
     */
    private $level;
    
    /**
     * Contains a list of instantiated levels.
     * 
     * @var array
     * 
     * @access private
     */
    private static $levelMap;
    
    /**
     * String representation of the level.
     * 
     * @var string
     * 
     * @access private
     */
    private $levelStr;
    
    /**
     * Equivalent syslog level.
     * 
     * @var integer
     * 
     * @access private
     */
    private $syslogEquivalent;
    
    
    /**
     * Constructor.
     *
     * @param integer $level            Level.
     * @param string  $levelStr         Level string.
     * @param integer $syslogEquivalent System log equivalent.
     * 
     * @access private
     */
    private function __construct($level, $levelStr, $syslogEquivalent)
    {
        $this->level            = $level;
        $this->levelStr         = $levelStr;
        $this->syslogEquivalent = $syslogEquivalent;
        
    }//end __construct()
    
    
    /**
     * Compares two logger levels.
     *
     * @param LoggerLevel $other Logger level.
     * 
     * @return boolean
     * 
     * @access public
     */
    public function equals(LoggerLevel $other) : bool
    {
        if ($other instanceof LoggerLevel) {
            
            if ($this->level === $other->getLevel()) {
                return true;
            }//end if
            
        } else {
            
            return false;
            
        }//end if
        
    }//end equals()
    
    
    /**
     * Returns an Off Level.
     * 
     * @return LoggerLevel
     * 
     * @access public
     */
    public static function getLevelOff() : LoggerLevel
    {
        if (!isset(static::$levelMap[self::OFF])) {
            static::$levelMap[static::OFF] = new LoggerLevel(static::OFF, 'OFF', LOG_ALERT);
        }//end if
        
        return static::$levelMap[static::OFF];
        
    }//end getLevelOff()
    
    
    /**
     * Returns a Fatal Level.
     * 
     * @return LoggerLevel
     * 
     * @access public
     */
    public static function getLevelFatal() : LoggerLevel
    {
        if (!isset(static::$levelMap[static::FATAL])) {
            static::$levelMap[self::FATAL] = new LoggerLevel(static::FATAL, 'FATAL', LOG_ALERT);
        }//end if
        
        return static::$levelMap[static::FATAL];
        
    }//end getLevelFatal()
    
    
    /**
     * Returns an Error Level.
     * 
     * @return LoggerLevel
     * 
     * @access public
     */
    public static function getLevelError() : LoggerLevel
    {
        if (!isset(static::$levelMap[static::ERROR])) {
            static::$levelMap[static::ERROR] = new LoggerLevel(static::ERROR, 'ERROR', LOG_ERR);
        }//end if
        
        return static::$levelMap[static::ERROR];
        
    }//end getLevelError()
    
    
    /**
     * Returns a Warn Level.
     * 
     * @return LoggerLevel
     * 
     * @access public
     */
    public static function getLevelWarn() : LoggerLevel
    {
        if (!isset(static::$levelMap[static::WARN])) {
            static::$levelMap[static::WARN] = new LoggerLevel(static::WARN, 'WARN', LOG_WARNING);
        }//end if
        
        return self::$levelMap[self::WARN];
        
    }//end getLevelWarn()
    
    
    /**
     * Returns an Info Level.
     * 
     * @return LoggerLevel
     * 
     * @access public
     */
    public static function getLevelInfo() : LoggerLevel
    {
        if (!isset(static::$levelMap[static::INFO])) {
            static::$levelMap[static::INFO] = new LoggerLevel(static::INFO, 'INFO', LOG_INFO);
        }//end if
        
        return static::$levelMap[static::INFO];
        
    }//end getLevelInfo()
    
    
    /**
     * Returns a Debug Level.
     * 
     * @return LoggerLevel
     * 
     * @access public
     */
    public static function getLevelDebug() : LoggerLevel
    {
        if (!isset(static::$levelMap[static::DEBUG])) {
            static::$levelMap[static::DEBUG] = new LoggerLevel(static::DEBUG, 'DEBUG', LOG_DEBUG);
        }//end if
        
        return static::$levelMap[static::DEBUG];
        
    }//end getLevelDebug()
    
    
    /**
     * Returns a Trace Level.
     * 
     * @return LoggerLevel
     * 
     * @access public
     */
    public static function getLevelTrace() : LoggerLevel
    {
        if (!isset(static::$levelMap[static::TRACE])) {
            static::$levelMap[static::TRACE] = new LoggerLevel(static::TRACE, 'TRACE', LOG_DEBUG);
        }//end if
        
        return static::$levelMap[static::TRACE];
        
    }//end getLevelTrace()
    
    
    /**
     * Returns an All Level.
     * 
     * @return LoggerLevel
     * 
     * @access public
     */
    public static function getLevelAll() : LoggerLevel
    {
        if (!isset(static::$levelMap[static::ALL])) {
            static::$levelMap[static::ALL] = new LoggerLevel(static::ALL, 'ALL', LOG_DEBUG);
        }//end if
        
        return static::$levelMap[static::ALL];
        
    }//end getLevelAll()
    
    
    /**
     * Return the syslog equivalent of this level as an integer.
     * 
     * @return integer
     * 
     * @access public
     */
    public function getSyslogEquivalent() : int
    {
        return $this->syslogEquivalent;
        
    }//end getSyslogEquivalent()
    
    
    /**
     * Returns true if this level has a higher or equal level than the level passed as argument, false otherwise.
     *
     * @param LoggerLevel $other Logger level.
     * 
     * @return boolean
     * 
     * @access public
     */
    public function isGreaterOrEqual(LoggerLevel $other) : bool
    {
        if ($this->level >= $other->getLevel()) {
            $out = true;
        } else {
            $out = false;
        }//end if
        
        return $out;
        
    }//end isGreaterOrEqual()
    
    
    /**
     * Returns the string representation of this level.
     * 
     * @return string
     * 
     * @access public
     */
    public function toString() : string
    {
        return $this->levelStr;
        
    }//end toString()
    
    
    /**
     * Returns the string representation of this level.
     * 
     * @return string
     * 
     * @access public
     */
    public function __toString() : string
    {
        return $this->toString();
        
    }//end __toString()
    
    
    /**
     * Returns the integer representation of this level.
     * 
     * @return integer
     * 
     * @access public
     */
    public function toInt() : int
    {
        return $this->level;
        
    }//end toInt()
    
    
    /**
     * Convert the input argument to a level. If the conversion fails, this method returns the provided default level.
     *
     * @param mixed       $arg     The value to convert to level.
     * @param LoggerLevel $default Value to return if conversion is not possible.
     * 
     * @return LoggerLevel
     * 
     * @access public
     */
    public static function toLevel($arg, LoggerLevel $defaultLevel = null) : LoggerLevel
    {
        if (is_int($arg)) {
            
            switch ($arg) {
                
                case static::ALL:
                    return static::getLevelAll();
                    
                case static::TRACE:
                    return static::getLevelTrace();
                    
                case static::DEBUG:
                    return static::getLevelDebug();
                    
                case static::INFO:
                    return static::getLevelInfo();
                    
                case static::WARN:
                    return static::getLevelWarn();
                    
                case static::ERROR:
                    return static::getLevelError();
                    
                case static::FATAL:
                    return static::getLevelFatal();
                    
                case static::OFF:
                    return static::getLevelOff();
                    
                default:
                    return $defaultLevel;
            }//end switch
            
        } else {
            
            switch (strtoupper($arg)) {
                
                case 'ALL':
                    return static::getLevelAll();
                    
                case 'TRACE':
                    return static::getLevelTrace();
                    
                case 'DEBUG':
                    return static::getLevelDebug();
                    
                case 'INFO':
                    return static::getLevelInfo();
                    
                case 'WARN':
                    return static::getLevelWarn();
                    
                case 'ERROR':
                    return static::getLevelError();
                    
                case 'FATAL':
                    return static::getLevelFatal();
                    
                case 'OFF':
                    return static::getLevelOff();
                    
                default:
                    return $defaultLevel;
            }//end switch
            
        }//end if
        
    }//end toLevel()
    
    
    /**
     * Get level.
     * 
     * @return integer
     * 
     * @access public
     */
    public function getLevel() : int
    {
        return $this->level;
        
    }//end getLevel()
    
    
}//end LoggerLevel class
