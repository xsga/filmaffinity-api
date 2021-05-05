<?php
/**
 * LoggerLayoutTTCC.
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
 * PHP Version 8
 *
 * @package    Log4php
 * @subpackage Layouts
 */

/**
 * Namespace.
 */
namespace log4php\layouts;

/**
 * Import dependencies.
 */
use log4php\LoggerLayout;
use log4php\LoggerLoggingEvent;

/**
 * LoggerLayoutTTCC.
 * 
 * TTCC layout format consists of <b>t</b>ime, <b>t</b>hread, <b>c</b>ategory and nested
 * diagnostic <b>c</b>ontext information, hence the name.
 * 
 * <p>Each of the four fields can be individually enabled or
 * disabled. The time format depends on the <b>DateFormat</b> used.</p>
 *
 * <p>If no dateFormat is specified it defaults to '%c'. 
 * See php {@link PHP_MANUAL#date} function for details.</p>
 *
 * Configurable parameters for this layout are:
 * - {@link $threadPrinting} (true|false) enable/disable pid reporting.
 * - {@link $categoryPrefixing} (true|false) enable/disable logger category reporting.
 * - {@link $contextPrinting} (true|false) enable/disable NDC reporting.
 * - {@link $microSecondsPrinting} (true|false) enable/disable micro seconds reporting in timestamp.
 * - {@link $dateFormat} (string) set date format. See php {@link PHP_MANUAL#date} function for details.
 *
 * An example how to use this layout:
 * 
 * {@example ../../examples/php/layout_ttcc.php 19}<br>
 * 
 * {@example ../../examples/resources/layout_ttcc.properties 18}<br>
 *
 * The above would print:<br>
 * <samp>02:28 [13714] INFO root - Hello World!</samp>
 */
class LoggerLayoutTTCC extends LoggerLayout
{

    /**
     * Thread printing.
     * 
     * @var boolean
     * 
     * @access protected
     */
    protected $threadPrinting = true;
    
    /**
     * Category prefixing.
     *
     * @var boolean
     *
     * @access protected
     */
    protected $categoryPrefixing = true;
    
    /**
     * Context printing.
     *
     * @var boolean
     *
     * @access protected
     */
    protected $contextPrinting = true;
    
    /**
     * Microseconds printing.
     *
     * @var boolean
     *
     * @access protected
     */
    protected $microSecondsPrinting = true;
    
    /**
     * Dateformat.
     * 
     * @var string
     * 
     * @access protected
     */
    protected $dateFormat = '%c';

    
    /**
     * Constructor.
     *
     * @param string $dateFormat Date format.
     * 
     * @access public
     */
    public function __construct($dateFormat='')
    {
        $log  = 'LoggerLayout TTCC is deprecated and will be removed in a future release.';
        $log .= ' Please use LoggerLayoutPattern instead.';
                
        $this->warn($log);
        
        if (!empty($dateFormat)) {
            $this->dateFormat = $dateFormat;
        }//end if
        
    }//end __construct()
    

    /**
     * SetThreadPrinting.
     * 
     * The <b>ThreadPrinting</b> option specifies whether the name of the current thread is part of log output or not.
     * This is true by default.
     * 
     * @param boolean $threadPrinting Boolean.
     * 
     * @return void
     * 
     * @access public
     */
    public function setThreadPrinting($threadPrinting) : void
    {
        $this->setBoolean('threadPrinting', $threadPrinting);
        
    }//end setThreadPrinting()
    

    /**
     * Returns value of the <b>ThreadPrinting</b> option.
     * 
     * @return boolean
     * 
     * @access public
     */
    public function getThreadPrinting() : bool
    {
        return $this->threadPrinting;
        
    }//end getThreadPrinting()
    

    /**
     * The <b>CategoryPrefixing</b> option specifies whether Category name is part of log output or not.
     * 
     * This is true by default.
     * 
     * @param boolean $categoryPrefixing Boolean.
     * 
     * @return void
     * 
     * @access public
     */
    public function setCategoryPrefixing($categoryPrefixing) : void
    {
        $this->setBoolean('categoryPrefixing', $categoryPrefixing);
        
    }//end setCategoryPrefixing()
    

    /**
     * Returns value of the <b>CategoryPrefixing</b> option.
     * 
     * @return boolean
     * 
     * @access public
     */
    public function getCategoryPrefixing() : bool
    {
        return $this->categoryPrefixing;
        
    }//end getCategoryPrefixing()
    

    /**
     * SetContextPrinting.
     * 
     * The <b>ContextPrinting</b> option specifies log output will include the nested context information 
     * belonging to the current thread. This is true by default.
     * 
     * @param boolean $contextPrinting Boolean.
     * 
     * @return void
     * 
     * @access public
     */
    public function setContextPrinting($contextPrinting) : void
    {
        $this->setBoolean('contextPrinting', $contextPrinting);
        
    }//end setContextPrinting()
    

    /**
     * Returns value of the <b>ContextPrinting</b> option.
     * 
     * @return boolean
     * 
     * @access public
     */
    public function getContextPrinting() : bool
    {
        return $this->contextPrinting;
        
    }//end getContextPrinting()
    
    
    /**
     * The <b>MicroSecondsPrinting</b> option specifies if microseconds infos should be printed at the end of timestamp.
     * 
     * This is true by default.
     * 
     * @param boolean $microSecondsPrinting Boolean.
     * 
     * @return void
     * 
     * @access public
     */
    public function setMicroSecondsPrinting($microSecondsPrinting) : void
    {
        $this->setBoolean('microSecondsPrinting', $microSecondsPrinting);
        
    }//end setMicroSecondsPrinting()
    

    /**
     * Returns value of the <b>MicroSecondsPrinting</b> option.
     * 
     * @return boolean
     * 
     * @access public
     */
    public function getMicroSecondsPrinting() : bool
    {
        return $this->microSecondsPrinting;
        
    }//end getMicroSecondsPrinting()
    
    
    /**
     * Set date format.
     * 
     * @param string $dateFormat Dateformat.
     * 
     * @return void
     * 
     * @access public
     */
    public function setDateFormat($dateFormat) : void
    {
        $this->setString('dateFormat', $dateFormat);
        
    }//end setDateFormat()
    
    
    /**
     * Get dateformat.
     * 
     * @return string
     * 
     * @access public
     */
    public function getDateFormat() : string
    {
        return $this->dateFormat;
        
    }//end getDateFormat()
    

    /**
     * Format.
     * 
     * In addition to the level of the statement and message, the returned string includes time, thread, category.
     * Time, thread, category are printed depending on options.
     *
     * @param LoggerLoggingEvent $event Event.
     * 
     * @return string
     * 
     * @access public
     */
    public function format(LoggerLoggingEvent $event) : string
    {
        $timeStamp = (float)$event->getTimeStamp();
        $format    = strftime($this->dateFormat, (int)$timeStamp);
        
        if ($this->microSecondsPrinting) {
            $usecs   = floor(($timeStamp - (int)$timeStamp) * 1000);
            $format .= sprintf(',%03d', $usecs);
        }//end if
            
        $format .= ' ';
        
        if ($this->threadPrinting) {
            $format .= '['.getmypid().'] ';
        }//end if
        
        $level   = $event->getLevel();
        $format .= $level.' ';
        
        if ($this->categoryPrefixing) {
            $format .= $event->getLoggerName().' ';
        }//end if
       
        if ($this->contextPrinting) {
            $ndc = $event->getNDC();
            if ($ndc !== null) {
                $format .= $ndc.' ';
            }//end if
        }//end if
        
        $format .= '- '.$event->getRenderedMessage();
        $format .= PHP_EOL;
        
        return $format;
        
    }//end format()
    

    /**
     * Ignores throws.
     * 
     * @return boolean
     * 
     * @access public
     */
    public function ignoresThrowable() : bool
    {
        return true;
        
    }//end ignoresThrowable()


}//end LoggerLayoutTTCC class
