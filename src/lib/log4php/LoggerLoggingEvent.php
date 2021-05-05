<?php
/**
 * LoggerLoggingEvent.
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
 * The internal representation of logging event.
 */
class LoggerLoggingEvent
{
    
    /**
     * Start time.
     * 
     * @var float
     * 
     * @access private
     */
    private static $startTime;
    
    /**
     * Fully Qualified Class Name of the calling category class.
     * 
     * @var string
     * 
     * @access private
     */
    private $fqcn;
    
    /**
     * Logger.
     * 
     * @var Logger
     * 
     * @access private
     */
    private $logger;
    
    /**
     * The category (logger) name. Please do not access it directly. Use the {@link getLoggerName()} method instead.
     * 
     * @var string
     * 
     * @access private
     * 
     * @deprecated
     */
    private $categoryName;
    
    /**
     * Level of the logging event.
     * 
     * @var LoggerLevel
     * 
     * @access protected
     */
    protected $level;
    
    /**
     * The nested diagnostic context (NDC) of logging event.
     * 
     * @var string
     * 
     * @access private
     */
    private $ndc;
    
    /**
     * Have we tried to do an NDC lookup? If we did, there is no need to do it again.
     * 
     * @var boolean
     * 
     * @access private
     */
    private $ndcLookupRequired = true;
    
    /**
     * The application supplied message of logging event.
     * 
     * @var mixed
     * 
     * @access private
     */
    private $message;
    
    /**
     * The application supplied message rendered through the log4php objet rendering mechanism.
     * 
     * @var string
     * 
     * @access private
     */
    private $renderedMessage;
    
    /**
     * The name of thread in which this logging event was generated.
     * 
     * @var mixed
     * 
     * @access private
     */
    private $threadName;
    
    /**
     * The number of seconds elapsed from 1/1/1970 until logging event was created plus microseconds if available.
     * 
     * @var float
     * 
     * @access public
     */
    public $timeStamp;
    
    /**
     * Location information for the caller.
     * 
     * @var LoggerLocationInfo
     * 
     * @access private
     */
    private $locationInfo;
    
    /**
     * Log4php internal representation of throwable.
     * 
     * @var LoggerThrowableInformation
     * 
     * @access private
     */
    private $throwableInfo;
    
    
    /**
     * Instantiate a LoggingEvent from the supplied parameters.
     *
     * Except {@link $timeStamp} all the other fields of LoggerLoggingEvent are filled when actually needed.
     *
     * @param string        $fqcn      Name of the caller class.
     * @param Logger|string $logger    The {@link Logger} category of this event or the logger name.
     * @param LoggerLevel   $level     The level of this event.
     * @param mixed         $message   The message of this event.
     * @param integer       $timeStamp The timestamp of this logging event.
     * @param \Exception    $throwable The throwable associated with logging event.
     * 
     * @access public
     */
    public function __construct(
        $fqcn,
        $logger,
        LoggerLevel $level,
        $message,
        $timeStamp = null,
        \Exception $throwable = null
    )
    {
        $this->fqcn = $fqcn;
        
        if (($logger instanceof Logger)) {
            
            $this->logger       = $logger;
            $this->categoryName = $logger->getName();
            
        } else {
            
            $this->categoryName = strval($logger);
            
        }//end if
        
        $this->level   = $level;
        $this->message = $message;
        
        if (($timeStamp !== null) && is_numeric($timeStamp)) {
            $this->timeStamp = $timeStamp;
        } else {
            $this->timeStamp = microtime(true);
        }//end if
        
        if (($throwable !== null) && ($throwable instanceof \Exception)) {
            $this->throwableInfo = new LoggerThrowableInformation($throwable);
        }//end if
        
    }//end __construct()
    
    
    /**
     * Returns the full qualified classname.
     * 
     * @return string
     * 
     * @access public
     */
    public function getFullQualifiedClassname() : string
    {
        return $this->fqcn;
        
    }//end getFullQualifiedClassname()
    
    
    /**
     * Set the location information for this logging event. The collected information is cached for future use.
     * 
     * @return LoggerLocationInfo
     * 
     * @access public
     */
    public function getLocationInformation() : LoggerLocationInfo
    {
        if ($this->locationInfo === null) {
            
            $locationInfo = array();
            $trace        = debug_backtrace();
            $prevHop      = null;
            
            // Make a downsearch to identify the caller.
            $hop = array_pop($trace);

            while ($hop !== null) {
                
                if (isset($hop['class'])) {
                    
                    // We are sometimes in functions = no class available: avoid php warning here.
                    //$className = strtolower(substr($hop['class'], 8));
                    $className = strtolower(str_replace('log4php\\', '', strtolower($hop['class'])));
                    
                    if (!empty($className)
                        && (($className === 'logger') || (strtolower(get_parent_class($className)) === 'logger'))
                    ) {
                        
                        $locationInfo['line'] = $hop['line'];
                        $locationInfo['file'] = $hop['file'];
                        break;
                        
                    }//end if
                    
                }//end if
                
                $prevHop = $hop;
                $hop     = array_pop($trace);
                
            }//end while
            
            if (isset($prevHop['class'])) {
                $locationInfo['class'] = $prevHop['class'];
            } else {
                $locationInfo['class'] = 'main';
            }//end if
            
            if ((isset($prevHop['function'])) 
                && ($prevHop['function'] !== 'include') 
                && ($prevHop['function'] !== 'include_once') 
                && ($prevHop['function'] !== 'require') 
                && ($prevHop['function'] !== 'require_once')
            ) {
                $locationInfo['function'] = $prevHop['function'];
            } else {
                $locationInfo['function'] = 'main';
            }//end if
                     
            $this->locationInfo = new LoggerLocationInfo($locationInfo);
            
        }//end if
        
        return $this->locationInfo;
        
    }//end getLocationInformation()
    
    
    /**
     * Return the level of this event. Use this form instead of directly accessing the {@link $level} field.
     * 
     * @return LoggerLevel
     * 
     * @access public
     */
    public function getLevel() : LoggerLevel
    {
        return $this->level;
        
    }//end getLevel()
    
    
    /**
     * Returns the logger which created the event.
     * 
     * @return Logger
     * 
     * @access public
     */
    public function getLogger() : Logger
    {
        return $this->logger;
        
    }//end getLogger()
    
    
    /**
     * Return the name of the logger. Use this form instead of directly accessing the {@link $categoryName} field.
     * 
     * @return string
     * 
     * @access public
     */
    public function getLoggerName() : string
    {
        return $this->categoryName;
        
    }//end getLoggerName()
    
    
    /**
     * Return the message for this logging event.
     * 
     * @return mixed
     * 
     * @access public
     */
    public function getMessage() : mixed
    {
        return $this->message;
        
    }//end getMessage()
    
    
    /**
     * This method returns the NDC for this event. The {@link LoggerNDC::get()} method should never be called directly.
     * 
     * @return string
     * 
     * @access public
     */
    public function getNDC() : string
    {
        if ($this->ndcLookupRequired) {
            
            $this->ndcLookupRequired = false;
            $this->ndc               = LoggerNDC::get();
            
        }//end if
        
        return $this->ndc;
        
    }//end getNDC()
    
    
    /**
     * Returns the the context corresponding to the <code>key</code> parameter.
     * 
     * @param string $key Key.
     * 
     * @return string
     * 
     * @access public
     */
    public function getMDC($key) : string
    {
        return LoggerMDC::get($key);
        
    }//end getMDC()
    
    
    /**
     * Returns the entire MDC context.
     * 
     * @return array
     * 
     * @access public
     */
    public function getMDCMap() : array
    {
        return LoggerMDC::getMap();
        
    }//end getMDCMap()
    
    
    /**
     * Render message.
     * 
     * @return string
     * 
     * @access public
     */
    public function getRenderedMessage() : string
    {
        if (($this->renderedMessage === null) && ($this->message !== null)) {
            
            if (is_string($this->message)) {
                $this->renderedMessage = $this->message;
            } else {
                $rendererMap = Logger::getHierarchy()->getRendererMap();
                $this->renderedMessage = $rendererMap->findAndRender($this->message);
            }//end if
            
        }//end if
        
        return $this->renderedMessage;
        
    }//end getRenderedMessage()
    
    
    /**
     * Returns the time when the application started, as a UNIX timestamp with microseconds.
     * 
     * @return float
     * 
     * @access public
     */
    public static function getStartTime() : float
    {
        if (!isset(static::$startTime)) {
            static::$startTime = microtime(true);
        }//end if
        
        return static::$startTime;
         
    }//end getStartTime(
    
    
    /**
     * Get timestamp.
     * 
     * @return float
     * 
     * @access public
     */
    public function getTimeStamp() : float
    {
        return $this->timeStamp;
        
    }//end getTimeStamp()
    
    
    /**
     * Returns the time in seconds passed from the beginning of execution to the time the event was constructed.
     * 
     * @return float
     * 
     * @access public
     */
    public function getRelativeTime() : float
    {
        return ($this->timeStamp - static::$startTime);
        
    }//end getRelativeTime()
    
    
    /**
     * Returns the time in milliseconds passed from the beginning of execution to the time the event was constructed.
     * 
     * @deprecated This method has been replaced by getRelativeTime.
     * 
     * @return integer
     * 
     * @access public
     */
    public function getTime() : int
    {
        $eventTime      = $this->getTimeStamp();
        $eventStartTime = static::getStartTime();
        
        return number_format((($eventTime - $eventStartTime) * 1000), 0, '', '');
        
    }//end getTime()
    
    
    /**
     * Get thread name.
     * 
     * @return mixed
     * 
     * @access public
     */
    public function getThreadName() : mixed
    {
        if ($this->threadName === null) {
            $this->threadName = (string)getmypid();
        }//end if
        
        return $this->threadName;
        
    }//end getThreadName()
    
    
    /**
     * Get throwable information.
     * 
     * @return mixed LoggerThrowableInformation
     * 
     * @access public
     */
    public function getThrowableInformation() : mixed
    {
        return $this->throwableInfo;
        
    }//end getThrowableInformation()
    
    
    /**
     * Serialize this object.
     * 
     * @return void
     * 
     * @access public
     */
    public function toString() : void
    {
        $out = serialize($this);
        
    }//end toString()
    
    
    /**
     * Avoid serialization of the {@link $logger} object.
     * 
     * @return array
     * 
     * @access public
     */
    public function __sleep() : array
    {
        
        return array(
                'fqcn',
                'categoryName',
                'level',
                'ndc',
                'ndcLookupRequired',
                'message',
                'renderedMessage',
                'threadName',
                'timeStamp',
                'locationInfo',
               );
        
    }//end __sleep()
    
    
}//end LoggerLoggingEvent class

LoggerLoggingEvent::getStartTime();
