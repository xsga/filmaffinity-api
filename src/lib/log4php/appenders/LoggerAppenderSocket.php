<?php
/**
 * LoggerAppenderSocket.
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
 * @subpackage Appenders
 */

/**
 * Namespace.
 */
namespace log4php\appenders;

/**
 * Import dependencies.
 */
use log4php\LoggerAppender;
use log4php\layouts\LoggerLayoutSerialized;
use log4php\LoggerLoggingEvent;

/**
 * LoggerAppenderSocket appends to a network socket.
 *
 * ## Configurable parameters: ##
 * 
 * - **remoteHost** - Target remote host.
 * - **port** - Target port (optional, defaults to 4446).
 * - **timeout** - Connection timeout in seconds (optional, defaults to 
 *     'default_socket_timeout' from php.ini)
 * 
 * The socket will by default be opened in blocking mode.
 */
class LoggerAppenderSocket extends LoggerAppender
{
    
    /**
     * Target host.
     * 
     * @var string
     * 
     * @access protected
     */
    protected $remoteHost;
    
    /**
     * Target port.
     * 
     * @var integer
     * 
     * @access protected
     */
    protected $port = 4446;
    
    /**
     * Connection timeout in ms.
     * 
     * @var string
     * 
     * @access protected
     */
    protected $timeout;
    
    
    /**
     * Override the default layout to use serialized.
     * 
     * @return LoggerLayoutSerialized
     * 
     * @access public
     */
    public function getDefaultLayout()
    {
        return new LoggerLayoutSerialized();
        
    }//end getDefaultLayout()
    
    
    /**
     * Activate options.
     * 
     * @return void
     * 
     * @access public
     */
    public function activateOptions() : void
    {
        if (empty($this->remoteHost)) {
            $this->warn('Required parameter [remoteHost] not set. Closing appender.');
            $this->closed = true;
            return;
        }//end if
    
        if (empty($this->timeout)) {
            $this->timeout = ini_get('default_socket_timeout');
        }//end if
    
        $this->closed = false;
        
    }//end activateOptions()
    
    
    /**
     * Append.
     * 
     * @param LoggerLoggingEvent $event Event.
     * 
     * @return void
     * 
     * @access public
     */
    public function append(LoggerLoggingEvent $event) : void
    {
        $socket = fsockopen($this->remoteHost, $this->port, $errno, $errstr, $this->timeout);
        
        if (!$socket) {
            $this->warn('Could not open socket to '.$this->remoteHost.':'.$this->port.'. Closing appender.');
            $this->closed = true;
            return;
        }//end if
    
        if (fwrite($socket, $this->layout->format($event)) === false) {
            $this->warn('Error writing to socket. Closing appender.');
            $this->closed = true;
        }//end if
        
        fclose($socket);
        
    }//end append()
    
    
    /**
     * Sets the target host.
     * 
     * @param string $hostname Hostname.
     * 
     * @return void
     * 
     * @access public
     */
    public function setRemoteHost($hostname) : void
    {
        $this->setString('remoteHost', $hostname);
        
    }//end setRemoteHost()
    
    
    /**
     * Sets the target port.
     * 
     * @param integer $port Port.
     * 
     * @return void
     * 
     * @access public
     */
    public function setPort($port) : void
    {
        $this->setPositiveInteger('port', $port);
        
    }//end setPort()
    
     
    /**
     * Sets the timeout.
     * 
     * @param integer $timeout Timeout.
     * 
     * @return void
     * 
     * @access public
     */
    public function setTimeout($timeout) : void
    {
        $this->setPositiveInteger('timeout', $timeout);
        
    }//end setTimeout()
    
    
    /**
     * Returns the target host.
     * 
     * @return string
     * 
     * @access public
     */
    public function getRemoteHost() : string
    {
        return $this->getRemoteHost();
        
    }//end getRemoteHost()
    
    
    /**
     * Returns the target port.
     * 
     * @return integer
     * 
     * @access public
     */
    public function getPort() : int
    {
        return $this->port;
        
    }//end getPort()
    
    
    /**
     * Returns the timeout.
     * 
     * @return integer
     * 
     * @access public
     */
    public function getTimeout() : int
    {
        return $this->timeout;
        
    }//end getTimeout()
    
    
}//end LoggerAppenderSocket class
