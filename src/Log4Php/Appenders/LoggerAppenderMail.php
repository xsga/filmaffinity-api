<?php

/**
 * LoggerAppenderMail.
 *
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements. See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
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
 * @link       http://logging.apache.org/log4php/docs/appenders/mail.html Appender documentation
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

/**
 * LoggerAppenderMail appends log events via email.
 *
 * This appender does not send individual emails for each logging requests but
 * will collect them in a buffer and send them all in a single email once the
 * appender is closed (i.e. when the script exists). Because of this, it may
 * not appropriate for long running scripts, in which case
 * LoggerAppenderMailEvent might be a better choice.
 *
 * This appender uses a layout.
 *
 * ## Configurable parameters: ##
 *
 * - **to** - Email address(es) to which the log will be sent. Multiple email
 *     addresses may be specified by separating them with a comma.
 * - **from** - Email address which will be used in the From field.
 * - **subject** - Subject of the email message.
 */
class LoggerAppenderMail extends LoggerAppender
{
    /**
     * Email address to put in From field of the email.
     *
     * @var string
     *
     * @access protected
     */
    protected $from = '';

    /**
     * The subject of the email.
     *
     * @var string
     *
     * @access protected
     */
    protected $subject = 'Log4Php Report';

    /**
     * One or more comma separated email addresses to which to send the email.
     *
     * @var string
     *
     * @access protected
     */
    protected $to = '';

    /**
     * Buffer which holds the email contents before it is sent.
     *
     * @var string
     *
     * @access protected
     */
    protected $body = '';

    /**
     * Append.
     *
     * @param LoggerLoggingEvent $event Event.
     *
     * @return void
     *
     * @access public
     * @see    LoggerAppender::append()
     */
    public function append(LoggerLoggingEvent $event): void
    {
        if ($this->layout !== null) {
            $this->body .= $this->layout->format($event);
        }//end if
    }

    /**
     * Close.
     *
     * @return void
     *
     * @access public
     * @see    LoggerAppender::close()
     */
    public function close(): void
    {
        if (!$this->closed) {
            $from = $this->from;
            $to   = $this->to;

            if (!empty($this->body) && !empty($from) && !empty($to) && ($this->layout !== null)) {
                $subject = $this->subject;

                mail(
                    $to,
                    $subject,
                    $this->layout->getHeader() . $this->body . $this->layout->getFooter(),
                    "From: {" . $from . "}\r\n"
                );
            }//end if

            $this->closed = true;
        }//end if
    }

    /**
     * Sets the 'subject' parameter.
     *
     * @param string $subject Subject.
     *
     * @return void
     *
     * @access public
     */
    public function setSubject(string $subject): void
    {
        $this->setString('subject', $subject);
    }

    /**
     * Returns the 'subject' parameter.
     *
     * @return string
     *
     * @access public
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * Sets the 'to' parameter.
     *
     * @param string $to To.
     *
     * @return void
     *
     * @access public
     */
    public function setTo(string $to): void
    {
        $this->setString('to', $to);
    }

    /**
     * Returns the 'to' parameter.
     *
     * @return string
     *
     * @access public
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * Sets the 'from' parameter.
     *
     * @param string $from From.
     *
     * @return void
     *
     * @access public
     */
    public function setFrom(string $from): void
    {
        $this->setString('from', $from);
    }

    /**
     * Returns the 'from' parameter.
     *
     * @return string
     *
     * @access public
     */
    public function getFrom(): string
    {
        return $this->from;
    }
}
