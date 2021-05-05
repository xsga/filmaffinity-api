<?php
/**
 * LoggeAppenderMailEvent.
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
 * @package    Log4php
 * @subpackage Appenders
 * @link       http://logging.apache.org/log4php/docs/appenders/mail-event.html Appender documentation
 */

/**
 * Namespace.
 */
namespace log4php\appenders;

/**
 * Import dependencies.
 */
use log4php\LoggerAppender;
use log4php\LoggerLoggingEvent;

/**
 * LoggerAppenderMailEvent appends individual log events via email.
 * 
 * This appender is similar to LoggerAppenderMail, except that it sends each 
 * each log event in an individual email message at the time when it occurs.
 * 
 * This appender uses a layout.
 * 
 * ## Configurable parameters: ##
 * 
 * - **to** - Email address(es) to which the log will be sent. Multiple email
 *     addresses may be specified by separating them with a comma.
 * - **from** - Email address which will be used in the From field.
 * - **subject** - Subject of the email message.
 * - **smtpHost** - Used to override the SMTP server. Only works on Windows.
 * - **port** - Used to override the default SMTP server port. Only works on 
 *     Windows.
 */
class LoggerAppenderMailEvent extends LoggerAppender
{

    /**
     * Email address to put in From field of the email.
     * 
     * @var string
     * 
     * @access protected
     */
    protected $from;

    /**
     * Mail server port (widnows only).
     * 
     * @var integer
     * 
     * @access protected
     */
    protected $port = 25;

    /**
     * Mail server hostname (windows only).
     * 
     * @var string
     * 
     * @access protected
     */
    protected $smtpHost;

    /**
     * The subject of the email.
     * 
     * @var string
     * 
     * @access protected
     */
    protected $subject = 'Log4php Report';

    /**
     * One or more comma separated email addresses to which to send the email.
     *  
     * @var string
     * 
     * @access protected
     */
    protected $to = null;
    
    /**
     * Indiciates whether this appender should run in dry mode.
     * 
     * @var boolean
     * 
     * @access protected
     * 
     * @deprecated
     */
    protected $dry = false;
    
    
    /**
     * Activate options.
     * 
     * @return void
     * 
     * @access public
     * @see    LoggerAppender::activateOptions()
     */
    public function activateOptions() : void
    {
        if (empty($this->to)) {
            
            $this->warn('Required parameter \'to\' not set. Closing appender.');
            $this->close = true;
            
            return;
            
        }//end if
        
        $sendmailFrom = ini_get('sendmail_from');
        
        if (empty($this->from) && empty($sendmailFrom)) {
            
            $this->warn('Required parameter \'from\' not set. Closing appender.');
            $this->close = true;
            
            return;
            
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
     * @see    LoggerAppender::append()
     */
    public function append(LoggerLoggingEvent $event) : void
    {

        $smtpHost     = $this->smtpHost;
        $prevSmtpHost = ini_get('SMTP');
        
        if (!empty($smtpHost)) {
            ini_set('SMTP', $smtpHost);
        }//end if
    
        $smtpPort     = $this->port;
        $prevSmtpPort = ini_get('smtp_port');
        
        if (($smtpPort > 0) && ($smtpPort < 65535)) {
            ini_set('smtp_port', $smtpPort);
        }//end if
    
        // On unix only sendmail_path, which is PHP_INI_SYSTEM i.e. not changeable here, is used.
        if (empty($this->from)) {
           $addHeader = '';
        } else {
            $addHeader = "From: {".$this->from."}\r\n";
        }//end if
    
        if (!$this->dry) {
            
            mail(
                $this->to, 
                $this->subject, 
                $this->layout->getHeader().$this->layout->format($event).$this->layout->getFooter($event), 
                $addHeader
            );
            
        } else {
            
            $log  = 'DRY MODE OF MAIL APP.: Send mail to: '.$this->to.' with additional headers \'';
            $log .= trim($addHeader).'\ and content: '.$this->layout->format($event);
            
            echo $log;
            
        }//end if
            
        ini_set('SMTP', $prevSmtpHost);
        ini_set('smtp_port', $prevSmtpPort);
        
    }//end append()
    
    
    /**
     * Sets the 'from' parameter.
     * 
     * @param string $from From.
     * 
     * @return void
     * 
     * @access public
     */
    public function setFrom($from) : void
    {
        $this->setString('from', $from);
        
    }//end setFrom()
    
    
    /**
     * Returns the 'from' parameter.
     * 
     * @return string
     * 
     * @access public
     */
    public function getFrom() : string
    {
        return $this->from;
        
    }//end getFrom()
    
    
    /**
     * Sets the 'port' parameter.
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
     * Returns the 'port' parameter.
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
     * Sets the 'smtpHost' parameter.
     * 
     * @param string $smtpHost SMTP host.
     * 
     * @return void
     * 
     * @access public
     */
    public function setSmtpHost($smtpHost) : void
    {
        $this->setString('smtpHost', $smtpHost);
        
    }//end setSmtpHost()
    
    
    /**
     * Returns the 'smtpHost' parameter.
     * 
     * @return string
     * 
     * @access public
     */
    public function getSmtpHost() : string
    {
        return $this->smtpHost;
        
    }//end getSmtpHost()
    
    
    /**
     * Sets the 'subject' parameter.
     * 
     * @param string $subject Subject.
     * 
     * @return void
     * 
     * @access public
     */
    public function setSubject($subject) : void
    {
        $this->setString('subject', $subject);
        
    }//end setSubject()
    
    
    /**
     * Returns the 'subject' parameter.
     * 
     * @return string
     * 
     * @access public
     */
    public function getSubject() : string
    {
        return $this->subject;
        
    }//end getSubject()
    
    
    /**
     * Sets the 'to' parameter.
     * 
     * @param string $to To.
     * 
     * @return void
     * 
     * @access public
     */
    public function setTo($to) : void
    {
        $this->setString('to', $to);
        
    }//end setTo()
    
    
    /**
     * Returns the 'to' parameter.
     * 
     * @return string
     * 
     * @access public
     */
    public function getTo() : string
    {
        return $this->to;
        
    }//end getTo()
    

    /**
     * Enables or disables dry mode.
     * 
     * @param boolean $dry Dry.
     * 
     * @return void
     * 
     * @access public
     */
    public function setDry($dry) : void
    {
        $this->setBoolean('dry', $dry);
        
    }//end setDry()


}//end LoggerAppenderMailEvent class
