<?php
/**
 * LoggerLayoutXml.
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
 * The output of the LoggerXmlLayout consists of a series of log4php:event elements. 
 * 
 * Configurable parameters: 
 * - {@link $locationInfo} - If set to true then the file name and line number 
 *   of the origin of the log statement will be included in output.
 * - {@link $log4jNamespace} - If set to true then log4j namespace will be used
 *   instead of log4php namespace. This can be usefull when using log viewers 
 *   which can only parse the log4j namespace such as Apache Chainsaw. 
 * 
 * <p>It does not output a complete well-formed XML file. 
 * The output is designed to be included as an external entity in a separate file to form
 * a correct XML file.</p>
 * 
 * Example:
 * 
 * {@example ../../examples/php/layout_xml.php 19}<br>
 * 
 * {@example ../../examples/resources/layout_xml.properties 18}<br>
 *
 * The above would print:
 * 
 * <pre>
 * <log4php:eventSet xmlns:log4php="http://logging.apache.org/log4php/" version="0.3" includesLocationInfo="true">
 *     <log4php:event logger="root" level="INFO" thread="13802" timestamp="1252456226491">
 *         <log4php:message><![CDATA[Hello World!]]></log4php:message>
 *         <log4php:locationInfo class="main" file="examples/php/layout_xml.php" line="6" method="main" />
 *     </log4php:event>
 * </log4php:eventSet>
 * </pre>
 */
class LoggerLayoutXml extends LoggerLayout
{

    /**
     * Log4J prefix.
     * 
     * @var string
     * 
     * @access public
     */
    const LOG4J_NS_PREFIX = 'log4j';

    /**
     * Log4J NS.
     * 
     * @var string
     * 
     * @access public
     */
    const LOG4J_NS = 'http://jakarta.apache.org/log4j/';
    
    /**
     * Log4J NS prefix.
     * 
     * @var string
     * 
     * @access public
     */
    const LOG4PHP_NS_PREFIX = 'log4php';
    
    /**
     * Log4PHP NS.
     * 
     * @var string
     * 
     * @access public
     */
    const LOG4PHP_NS = 'http://logging.apache.org/log4php/';
    
    /**
     * CDATA start.
     * 
     * @var string
     * 
     * @access public
     */
    const CDATA_START = '<![CDATA[';
    
    /**
     * CDATA end.
     * 
     * @var string
     * 
     * @access public
     */
    const CDATA_END = ']]>';
    
    /**
     * CDATA pseudo end.
     * 
     * @var string
     * 
     * @access public
     */
    const CDATA_PSEUDO_END = ']]&gt;';
    
    /**
     * CDATA embedded end.
     * 
     * @var string
     * 
     * @access public
     */
    const CDATA_EMBEDDED_END = ']]>]]&gt;<![CDATA[';

    /**
     * If set to true then the file name and line number of the origin of the log statement will be output.
     * 
     * @var boolean
     * 
     * @access protected
     */
    protected $locationInfo = true;
  
    /**
     * If set to true, log4j namespace will be used instead of the log4php namespace.
     * 
     * @var boolean
     * 
     * @access protected
     */
    protected $log4jNamespace = false;
    
    /**
     * The namespace in use.
     * 
     * @var string
     * 
     * @access protected
     */
    protected $namespace = self::LOG4PHP_NS;
    
    /**
     * The namespace prefix in use.
     * 
     * @var string
     * 
     * @access protected
     */
    protected $namespacePrefix = self::LOG4PHP_NS_PREFIX;
     
    
    /**
     * Activate option.
     * 
     * @return void
     * 
     * @access public
     */
    public function activateOptions() : void
    {
        if ($this->getLog4jNamespace()) {
            $this->namespace        = static::LOG4J_NS;
            $this->namespacePrefix  = static::LOG4J_NS_PREFIX;
        } else {
            $this->namespace        = static::LOG4PHP_NS;
            $this->namespacePrefix  = static::LOG4PHP_NS_PREFIX;
        }//end if
        
    }//end activaeOptions()
    
    
    /**
     * Get header.
     * 
     * @return string
     * 
     * @access public
     */
    public function getHeader() : string
    {
        $out  = '<'.$this->namespacePrefix.':eventSet xmlns:'.$this->namespacePrefix.'="'.$this->namespace.'" ';
        $out .= 'version="0.3" includesLocationInfo="'.($this->getLocationInfo() ? 'true' : 'false').'">';
        $out .= PHP_EOL;
        
        return $out;
        
    }//end getHeader()
    

    /**
     * Formats a LoggerLoggingEvent in conformance with the log4php.dtd.
     *
     * @param LoggerLoggingEvent $event Event.
     * 
     * @return string
     * 
     * @access public
     */
    public function format(LoggerLoggingEvent $event) : string
    {
        $ns = $this->namespacePrefix;
        
        $loggerName = $event->getLoggerName();
        $timeStamp  = number_format((float)($event->getTimeStamp() * 1000), 0, '', '');
        $thread     = $event->getThreadName();
        $level      = $event->getLevel()->toString();

        $buf  = '<'.$ns.':event logger="'.$loggerName.'" level="'.$level.'" thread="'.$thread.'" ';
        $buf .= 'timestamp="'.$timeStamp.'">'.PHP_EOL;
        $buf .= '<'.$ns.':message>'; 
        $buf .= $this->encodeCDATA($event->getRenderedMessage()); 
        $buf .= '</'.$ns.':message>'.PHP_EOL;

        $ndc = $event->getNDC();
        if (!empty($ndc)) {
            $buf .= '<'.$ns.':NDC><![CDATA[';
            $buf .= $this->encodeCDATA($ndc);
            $buf .= ']]></'.$ns.':NDC>'.PHP_EOL;
        }//end if
        
        $mdcMap = $event->getMDCMap();
        if (!empty($mdcMap)) {
            $buf .= '<'.$ns.':properties>'.PHP_EOL;
            foreach ($mdcMap as $name => $value) {
                $buf .= '<'.$ns.':data name="'.$name.'" value="'.$value.'" />'.PHP_EOL;
            }//end foreach
            $buf .= '</'.$ns.':properties>'.PHP_EOL;
        }//end if

        if ($this->getLocationInfo()) {
            $locationInfo = $event->getLocationInformation();
            $buf .= '<'.$ns.':locationInfo class="'.$locationInfo->getClassName().'" ';
            $buf .= 'file="'.htmlentities($locationInfo->getFileName(), ENT_QUOTES).'" ';
            $buf .= 'line="'.$locationInfo->getLineNumber().'" method="'.$locationInfo->getMethodName().'"/>'.PHP_EOL;
        }//end if

        $buf .= '</'.$ns.':event>'.PHP_EOL;
        
        return $buf;
        
    }//end format()
    
    
    /**
     * Get footer.
     * 
     * @return string
     * 
     * @access public
     */
    public function getFooter() : string
    {
        return '</'.$this->namespacePrefix.':eventSet>'.PHP_EOL;
        
    }//end getFooter()
    
    
    /**
     * Whether or not file name and line number will be included in the output.
     * 
     * @return boolean
     * 
     * @access public
     */
    public function getLocationInfo() : bool
    {
        return $this->locationInfo;
        
    }//end getLocationInfo()
    
  
    /**
     * The $locationInfo} option takes a boolean value. 
     * 
     * By default, it is set to false which means there will be no location information output by this layout.
     * If the the option is set to true, then the file name and line number of the statement at the
     * origin of the log statement will be output.
     * 
     * @param string $flag Flag.
     * 
     * @return void
     * 
     * @access public
     */
    public function setLocationInfo($flag) : void
    {
        $this->setBoolean('locationInfo', $flag);
        
    }//end setLocationInfo()
    
  
    /**
     * Get log4j namespace.
     * 
     * @return boolean
     * 
     * @access public
     */
    public function getLog4jNamespace() : bool
    {
        return $this->log4jNamespace;
         
    }//end getLog4jNamespace()
     

    /**
     * Set log4j namespace.
     * 
     * @param boolean $flag Flag.
     * 
     * @return void
     * 
     * @access public
     */
    public function setLog4jNamespace($flag) : void
    {
        $this->setBoolean('log4jNamespace', $flag);
        
    }//end setLog4jNamespace()
    
    
    /**
     * Encases a string in CDATA tags, and escapes any existing CDATA end tags already present in the string.
     * 
     * @param string $string String.
     * 
     * @return string
     * 
     * @access private
     */
    private function encodeCDATA($string) : string
    {
        $string = str_replace(static::CDATA_END, static::CDATA_EMBEDDED_END, $string);
        
        return static::CDATA_START . $string . static::CDATA_END;
        
    }//end encodeCDATA()
    
    
}//end LoggerLayoutXml class
