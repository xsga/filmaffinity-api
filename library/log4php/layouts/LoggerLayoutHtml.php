<?php
/**
 * LoggerLayoutHtml.
 * 
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements.  See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
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
 * @subpackage Layouts
 */

namespace log4php\layouts;

use log4php\LoggerLayout;
use log4php\LoggerLoggingEvent;
use log4php\LoggerLevel;

/**
 * This layout outputs events in a HTML table.
 *
 * Configurable parameters for this layout are:
 * 
 * - title
 * - locationInfo
 *
 * An example for this layout:
 * 
 * {@example ../../examples/php/layout_html.php 19}<br>
 * 
 * The corresponding XML file:
 * 
 * {@example ../../examples/resources/layout_html.properties 18}
 * 
 * The above will print a HTML table that looks, converted back to plain text, like the following:<br>
 * <pre>
 *    Log session start time Wed Sep 9 00:11:30 2009
 *
 *    Time Thread Level Category   Message
 *    0    8318   INFO  root       Hello World!
 * </pre>
 */
class LoggerLayoutHtml extends LoggerLayout
{
    
    /**
     * Location info.
     * 
     * The <b>LocationInfo</b> option takes a boolean value. By default, it is set to false which means
     * there will be no location information output by this layout. If the the option is set to true,
     * then the file name and line number of the statement at the origin of the log statement will be output.
     *
     * <p>If you are embedding this layout within a LoggerAppenderMail or a LoggerAppenderMailEvent then make sure
     * to set the <b>LocationInfo</b> option of that appender as well.
     * 
     * @var boolean
     * 
     * @access protected
     */
    protected $locationInfo = false;
    
    /**
     * The <b>Title</b> option takes a String value.
     * 
     * This option sets the document title of the generated HTML document. Defaults to 'Log4php Log Messages'.
     * 
     * @var string
     * 
     * @access protected
     */
    protected $title = 'Log4php Log Messages';
    
    
    /**
     * The <b>LocationInfo</b> option takes a boolean value.
     * 
     * By default, it is set to false which means there will be no location information output by this layout.
     * If the the option is set to true, then the file name and line number of the statement at the origin of the
     * log statement will be output.
     *
     * <p>If you are embedding this layout within a LoggerAppenderMail or a LoggerAppenderMailEvent then make sure
     * to set the <b>LocationInfo</b> option of that appender as well.
     * 
     * @param boolean $flag Flag.
     * 
     * @return void
     * 
     * @access public
     */
    public function setLocationInfo($flag)
    {
        $this->setBoolean('locationInfo', $flag);
        
    }//end setLocationInfo()
    

    /**
     * Returns the current value of the <b>LocationInfo</b> option.
     * 
     * @return boolean
     * 
     * @access public
     */
    public function getLocationInfo()
    {
        return $this->locationInfo;
        
    }//end getLocationInfo()
    
    
    /**
     * The <b>Title</b> option takes a String value.
     * 
     * This option sets the document title of the generated HTML document. Defaults to 'Log4php Log Messages'.
     * 
     * @param string $title Title.
     * 
     * @return void
     * 
     * @access public
     */
    public function setTitle($title)
    {
        $this->setString('title', $title);
        
    }//end setTitle()
    

    /**
     * Returns the current value of the <b>Title</b> option.
     * 
     * @return string
     * 
     * @access public
     */
    public function getTitle()
    {
        return $this->title;
        
    }//end getTitle()
    
    
    /**
     * Returns the content type output by this layout, i.e "text/html".
     * 
     * @return string
     * 
     * @access public
     */
    public function getContentType()
    {
        return 'text/html';
        
    }//end getContentType()
    
    
    /**
     * Format.
     * 
     * @param LoggerLoggingEvent $event Event.
     * 
     * @return string
     * 
     * @access public
     */
    public function format(LoggerLoggingEvent $event)
    {
        $sbuf  = PHP_EOL.'<tr>'.PHP_EOL;
        $sbuf .= '<td>';
        $sbuf .= round(1000 * $event->getRelativeTime());
        $sbuf .= '</td>'.PHP_EOL;
        $sbuf .= '<td title="'.$event->getThreadName().' thread">';
        $sbuf .= $event->getThreadName();
        $sbuf .= '</td>'.PHP_EOL;
        $sbuf .= '<td title="Level">';
        
        $level = $event->getLevel();
        
        if ($level->equals(LoggerLevel::getLevelDebug()) === true) {
            $sbuf .= '<font color="#339933">'.$level.'</font>';
        } elseif ($level->equals(LoggerLevel::getLevelWarn()) === true) {
            $sbuf .= '<font color="#993300"><strong>'.$level.'</strong></font>';
        } else {
            $sbuf .= $level;
        }//end if
        
        $sbuf .= '</td>'.PHP_EOL;
        $sbuf .= '<td title="'.htmlentities($event->getLoggerName(), ENT_QUOTES).' category">';
        $sbuf .= htmlentities($event->getLoggerName(), ENT_QUOTES);
        $sbuf .= '</td>'.PHP_EOL;
    
        if ($this->locationInfo === true) {
            
            $locInfo = $event->getLocationInformation();
            
            $sbuf .= '<td>';
            $sbuf .= htmlentities($locInfo->getFileName(), ENT_QUOTES).':'.$locInfo->getLineNumber();
            $sbuf .= '</td>'.PHP_EOL;
            
        }//end if

        $sbuf .= '<td title="Message">';
        $sbuf .= htmlentities($event->getRenderedMessage(), ENT_QUOTES);
        $sbuf .= '</td>'.PHP_EOL;
        $sbuf .= '</tr>'.PHP_EOL;
        
        if ($event->getNDC() !== null) {
            $sbuf .= '<tr><td bgcolor="#EEEEEE" style="font-size : xx-small;" colspan="6"';
            $sbuf .= ' title="Nested Diagnostic Context">';
            $sbuf .= 'NDC: '.htmlentities($event->getNDC(), ENT_QUOTES);
            $sbuf .= '</td></tr>'.PHP_EOL;
        }//end if
        
        return $sbuf;
        
    }//end format()
    

    /**
     * Returns appropriate HTML headers.
     * 
     * @return string
     * 
     * @access public
     */
    public function getHeader()
    {
        $sbuf  = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"';
        $sbuf  = ' "http://www.w3.org/TR/html4/loose.dtd">'.PHP_EOL;
        $sbuf .= '<html>'.PHP_EOL;
        $sbuf .= '<head>'.PHP_EOL;
        $sbuf .= '<title>'.$this->title.'</title>'.PHP_EOL;
        $sbuf .= '<style type="text/css">'.PHP_EOL;
        $sbuf .= '<!--'.PHP_EOL;
        $sbuf .= 'body, table {font-family: arial,sans-serif; font-size: x-small;}'.PHP_EOL;
        $sbuf .= 'th {background: #336699; color: #FFFFFF; text-align: left;}'.PHP_EOL;
        $sbuf .= '-->'.PHP_EOL;
        $sbuf .= '</style>'.PHP_EOL;
        $sbuf .= '</head>'.PHP_EOL;
        $sbuf .= '<body bgcolor="#FFFFFF" topmargin="6" leftmargin="6">'.PHP_EOL;
        $sbuf .= '<hr size="1" noshade>'.PHP_EOL;
        $sbuf .= 'Log session start time '.strftime('%c', time()).'<br>'.PHP_EOL;
        $sbuf .= '<br>'.PHP_EOL;
        $sbuf .= '<table cellspacing="0" cellpadding="4" border="1" bordercolor="#224466" width="100%">'.PHP_EOL;
        $sbuf .= '<tr>'.PHP_EOL;
        $sbuf .= '<th>Time</th>'.PHP_EOL;
        $sbuf .= '<th>Thread</th>'.PHP_EOL;
        $sbuf .= '<th>Level</th>'.PHP_EOL;
        $sbuf .= '<th>Category</th>'.PHP_EOL;
        
        if ($this->locationInfo === true) {
            $sbuf .= '<th>File:Line</th>'.PHP_EOL;
        }//end if
        
        $sbuf .= '<th>Message</th>'.PHP_EOL;
        $sbuf .= '</tr>'.PHP_EOL;

        return $sbuf;
        
    }//end getHeader()
    

    /**
     * Returns the appropriate HTML footers.
     * 
     * @return string
     * 
     * @access public
     */
    public function getFooter()
    {
        $sbuf  = '</table>'.PHP_EOL;
        $sbuf .= '<br>'.PHP_EOL;
        $sbuf .= '</body></html>';

        return $sbuf;
        
    }//end getFooter()
    
    
}//end LoggerLayoutHtml class
