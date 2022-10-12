<?php

/**
 * LoggerAppenderSyslog.
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
use Log4Php\LoggerLoggingEvent;
use Log4Php\LoggerLevel;

/**
 * Log events to a system log using the PHP syslog() function.
 *
 * This appenders requires a layout.
 *
 * ## Configurable parameters: ##
 *
 * - **ident** - The ident of the syslog message.
 * - **priority** - The priority for the syslog message (used when overriding
 *     priority).
 * - **facility** - The facility for the syslog message
 * - **overridePriority** - If set to true, the message priority will always
 *     use the value defined in {@link $priority}, otherwise the priority will
 *     be determined by the message's log level.
 * - **option** - The option value for the syslog message.
 *
 * Recognised syslog options are:
 *
 * - CONS      - if there is an error while sending data to the system logger, write directly to the system console
 * - NDELAY - open the connection to the logger immediately
 * - ODELAY - delay opening the connection until the first message is logged (default)
 * - PERROR - print log message also to standard error
 * - PID    - include PID with each message
 *
 * Multiple options can be set by delimiting them with a pipe character,
 * e.g.: "CONS|PID|PERROR".
 *
 * Recognised syslog priorities are:
 *
 * - EMERG
 * - ALERT
 * - CRIT
 * - ERR
 * - WARNING
 * - NOTICE
 * - INFO
 * - DEBUG
 *
 * Levels are mapped as follows:
 *
 * - <b>FATAL</b> to LOG_ALERT
 * - <b>ERROR</b> to LOG_ERR
 * - <b>WARN</b> to LOG_WARNING
 * - <b>INFO</b> to LOG_INFO
 * - <b>DEBUG</b> to LOG_DEBUG
 * - <b>TRACE</b> to LOG_DEBUG
 */
class LoggerAppenderSyslog extends LoggerAppender
{
    /**
     * The ident string is added to each message. Typically the name of your application.
     *
     * @var string
     *
     * @access protected
     */
    protected $ident = 'Apache log4php';

    /**
     * The syslog priority to use when overriding priority. This setting is required if overridePriority is set true.
     *
     * @var string
     *
     * @access protected
     */
    protected $priority = '';

    /**
     * The option used when opening the syslog connection.
     *
     * @var string
     *
     * @access protected
     */
    protected $option = 'PID|CONS';

    /**
     * The facility value indicates the source of the message.
     *
     * @var string
     *
     * @access protected
     */
    protected $facility = 'USER';

    /**
     * If set to true, the message priority will always use the value defined in $priority.
     *
     * Otherwise the priority will be determined by the message's log level.
     *
     * @var boolean
     *
     * @access protected
     */
    protected $overridePriority = false;

    /**
     * Holds the int value of the $priority.
     *
     * @var integer
     *
     * @access private
     */
    private $intPriority = 0;

    /**
     * Holds the int value of the $facility.
     *
     * @var integer
     *
     * @access private
     */
    private $intFacility = 0;

    /**
     * Holds the int value of the $option.
     *
     * @var integer
     *
     * @access private
     */
    private $intOption = 0;

    /**
     * Sets the $ident.
     *
     * @param string $ident Ident.
     *
     * @return void
     *
     * @access public
     */
    public function setIdent(string $ident): void
    {
        $this->ident = $ident;
    }

    /**
     * Sets the priority.
     *
     * @param string $priority Priority.
     *
     * @return void
     *
     * @access public
     */
    public function setPriority(string $priority): void
    {
        $this->priority = $priority;
    }

    /**
     * Sets the facility.
     *
     * @param string $facility Facility.
     *
     * @return void
     *
     * @access public
     */
    public function setFacility(string $facility): void
    {
        $this->facility = $facility;
    }

    /**
     * Sets the overridePriority.
     *
     * @param boolean $overridePriority Override priority.
     *
     * @return void
     *
     * @access public
     */
    public function setOverridePriority(bool $overridePriority): void
    {
        $this->overridePriority = $overridePriority;
    }

    /**
     * Sets the 'option' parameter.
     *
     * @param string $option Option.
     *
     * @return void
     *
     * @access public
     */
    public function setOption(string $option): void
    {
        $this->option = $option;
    }

    /**
     * Returns the 'ident' parameter.
     *
     * @return string
     *
     * @access public
     */
    public function getIdent(): string
    {
        return $this->ident;
    }

    /**
     * Returns the 'priority' parameter.
     *
     * @return string
     *
     * @access public
     */
    public function getPriority(): string
    {
        return $this->priority;
    }

    /**
     * Returns the 'facility' parameter.
     *
     * @return string
     *
     * @access public
     */
    public function getFacility(): string
    {
        return $this->facility;
    }

    /**
     * Returns the 'overridePriority' parameter.
     *
     * @return boolean
     *
     * @access public
     */
    public function getOverridePriority(): bool
    {
        return $this->overridePriority;
    }

    /**
     * Returns the 'option' parameter.
     *
     * @return string
     *
     * @access public
     */
    public function getOption(): string
    {
        return $this->option;
    }

    /**
     * Activate option.
     *
     * @return void
     *
     * @access public
     */
    public function activateOptions(): void
    {
        $this->intPriority = $this->parsePriority();
        $this->intOption   = $this->parseOption();
        $this->intFacility = (int)$this->parseFacility();
        $this->closed      = false;
    }

    /**
     * Close.
     *
     * @return void
     *
     * @access public
     */
    public function close(): void
    {
        if (!$this->closed) {
            closelog();
            $this->closed = true;
        }//end if
    }

    /**
     * Appends the event to syslog.
     *
     * Log is opened and closed each time because if it is not closed, it can cause the Apache httpd server to log
     * to whatever ident/facility was used in openlog().
     *
     * @param LoggerLoggingEvent $event Event.
     *
     * @return void
     *
     * @access public
     */
    public function append(LoggerLoggingEvent $event): void
    {
        $priority = $this->getSyslogPriority($event->getLevel());
        $message  = $this->layout->format($event);

        openlog($this->ident, $this->intOption, $this->intFacility);
        syslog($priority, $message);
        closelog();
    }

    /**
     * Determines which syslog priority to use based on the given level.
     *
     * @param LoggerLevel $level Level.
     *
     * @return integer
     *
     * @access private
     */
    private function getSyslogPriority(LoggerLevel $level): int
    {
        if ($this->overridePriority) {
            return $this->intPriority;
        }//end if

        return $level->getSyslogEquivalent();
    }

    /**
     * Parses a syslog option string and returns the correspodning int value.
     *
     * @return integer
     *
     * @access private
     */
    private function parseOption(): int
    {
        $value = 0;
        $options = explode('|', $this->option);

        foreach ($options as $option) {
            if (!empty($option)) {
                $constant = 'LOG_' . trim($option);
                if (defined($constant)) {
                    $value |= constant($constant);
                } else {
                    $log  = 'log4php: Invalid syslog option provided: $option. ';
                    $log .= 'Whole option string: ' . $this->option . '.';
                    trigger_error($log, E_USER_WARNING);
                }//end if
            }//end if
        }//end foreach

        return $value;
    }

    /**
     * Parses the facility string and returns the corresponding int value.
     *
     * @return string
     *
     * @access private
     */
    private function parseFacility(): string
    {
        if (!empty($this->facility)) {
            $constant = 'LOG_' . trim($this->facility);
            if (defined($constant)) {
                return constant($constant);
            } else {
                trigger_error('log4php: Invalid syslog facility provided: ' . $this->facility . '.', E_USER_WARNING);
            }//end if
        }//end if

        return '';
    }

    /**
     * Parses the priority string and returns the corresponding int value.
     *
     * @return mixed
     *
     * @access private
     */
    private function parsePriority(): mixed
    {
        if (!empty($this->priority)) {
            $constant = 'LOG_' . trim($this->priority);
            if (defined($constant)) {
                return constant($constant);
            } else {
                trigger_error('log4php: Invalid syslog priority provided: ' . $this->priority . '.', E_USER_WARNING);
            }//end if
        }//end if

        return null;
    }
}
