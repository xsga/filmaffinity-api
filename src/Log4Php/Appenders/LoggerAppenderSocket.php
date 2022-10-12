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
 * @package    Log4Php
 * @subpackage Appenders
 */

/**
 * Namespace.
 */
namespace Log4Php\Appenders;

/**
 * Import dependencies.
 */
use Log4Php\LoggerAppender;
use Log4Php\Layouts\LoggerLayoutSerialized;
use Log4Php\LoggerLoggingEvent;

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
    protected $remoteHost = '';

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
     * @var int
     *
     * @access protected
     */
    protected $timeout = 0;

    /**
     * Override the default layout to use serialized.
     *
     * @return LoggerLayoutSerialized
     *
     * @access public
     */
    public function getDefaultLayout(): LoggerLayoutSerialized
    {
        return new LoggerLayoutSerialized();
    }

    /**
     * Activate options.
     *
     * @return void
     *
     * @access public
     */
    public function activateOptions(): void
    {
        if (empty($this->remoteHost)) {
            $this->warn('Required parameter [remoteHost] not set. Closing appender.');
            $this->closed = true;
            return;
        }//end if

        if ($this->timeout === 0) {
            $this->timeout = (int)ini_get('default_socket_timeout');
        }//end if

        $this->closed = false;
    }

    /**
     * Append.
     *
     * @param LoggerLoggingEvent $event Event.
     *
     * @return void
     *
     * @access public
     */
    public function append(LoggerLoggingEvent $event): void
    {
        $socket = fsockopen($this->remoteHost, $this->port, $errno, $errstr, $this->timeout);

        if (!$socket) {
            $this->warn('Could not open socket to ' . $this->remoteHost . ':' . $this->port . '. Closing appender.');
            $this->warn("Error code: $errno ($errstr)");
            $this->closed = true;
            return;
        }//end if

        if (fwrite($socket, $this->layout->format($event)) === false) {
            $this->warn('Error writing to socket. Closing appender.');
            $this->closed = true;
        }//end if

        fclose($socket);
    }

    /**
     * Sets the target host.
     *
     * @param string $hostname Hostname.
     *
     * @return void
     *
     * @access public
     */
    public function setRemoteHost(string $hostname): void
    {
        $this->setString('remoteHost', $hostname);
    }

    /**
     * Sets the target port.
     *
     * @param integer $port Port.
     *
     * @return void
     *
     * @access public
     */
    public function setPort(int $port): void
    {
        $this->setPositiveInteger('port', $port);
    }

    /**
     * Sets the timeout.
     *
     * @param integer $timeout Timeout.
     *
     * @return void
     *
     * @access public
     */
    public function setTimeout(int $timeout): void
    {
        $this->setPositiveInteger('timeout', $timeout);
    }

    /**
     * Returns the target host.
     *
     * @return string
     *
     * @access public
     */
    public function getRemoteHost(): string
    {
        return $this->getRemoteHost();
    }

    /**
     * Returns the target port.
     *
     * @return integer
     *
     * @access public
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * Returns the timeout.
     *
     * @return integer
     *
     * @access public
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }
}
