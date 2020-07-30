<?php
/**
 * LoggerConfigurationAdapterINI.
 * 
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements. See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
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
 * @subpackage Configurators
 */

namespace log4php\configurators;

use log4php\LoggerException;

/**
 * Converts ini configuration files to a PHP array.
 * 
 * These used to be called "properties" files (inherited from log4j), and that file extension is still supported. 
 */
class LoggerConfigurationAdapterINI implements LoggerConfigurationAdapter
{
    
    /**
     * Name to assign to the root logger.
     * 
     * @var string
     * 
     * @access public
     */
    const ROOT_LOGGER_NAME = 'root';

    /**
     * Prefix used for defining logger additivity.
     * 
     * @var string
     * 
     * @access public
     */
    const ADDITIVITY_PREFIX = 'log4php.additivity.';
    
    /**
     * Prefix used for defining logger threshold.
     * 
     * @var string
     * 
     * @access public
     */
    const THRESHOLD_PREFIX = 'log4php.threshold';
    
    /**
     * Prefix used for defining the root logger.
     * 
     * @var string
     * 
     * @access public
     */
    const ROOT_LOGGER_PREFIX = 'log4php.rootLogger';
    
    /**
     * Prefix used for defining a logger.
     * 
     * @var string
     * 
     * @access public
     */
    const LOGGER_PREFIX = 'log4php.logger.';
    
    /**
     * Prefix used for defining an appender.
     * 
     * @var string
     * 
     * @access public
     */
    const APPENDER_PREFIX = 'log4php.appender.';
    
    /**
     * Prefix used for defining a renderer.
     * 
     * @var string
     * 
     * @access public
     */
    const RENDERER_PREFIX = 'log4php.renderer.';
    
    /**
     * Holds the configuration.
     * 
     * @var array
     * 
     * @access private
     */
    private $config = array();
    
    
    /**
     * Loads and parses the INI configuration file.
     * 
     * @param string $url Path to the config file.
     * 
     * @return array
     * 
     * @access private
     * 
     * @throws LoggerException
     */
    private function load($url)
    {
        if (file_exists($url) === false) {
            throw new LoggerException('File ['.$url.'] does not exist.');
        }//end if
        
        $properties = @parse_ini_file($url, true);
        
        if ($properties === false) {
            $error = error_get_last();
            throw new LoggerException('Error parsing configuration file: '.$error['message']);
        }//end if
        
        return $properties;
        
    }//end load()
    
    
    /**
     * Converts the provided INI configuration file to a PHP array config.
     *
     * @param string $path Path to the config file.
     * 
     * @return array
     * 
     * @access public
     * 
     * @throws LoggerException If the file cannot be loaded or parsed.
     */
    public function convert($path)
    {
        // Load the configuration.
        $properties = $this->load($path);
        
        // Parse threshold.
        if (isset($properties[static::THRESHOLD_PREFIX]) === true) {
            $this->config['threshold'] = $properties[static::THRESHOLD_PREFIX]; 
        }//end if
        
        // Parse root logger.
        if (isset($properties[static::ROOT_LOGGER_PREFIX]) === true) {
            $this->parseLogger($properties[static::ROOT_LOGGER_PREFIX], static::ROOT_LOGGER_NAME);
        }//end if
              
        foreach ($properties as $key => $value) {
            // Parse loggers.
            if ($this->beginsWith($key, static::LOGGER_PREFIX) === true) {
                $name = substr($key, strlen(static::LOGGER_PREFIX));
                $this->parseLogger($value, $name);
            }//end if
            
            // Parse additivity.
            if ($this->beginsWith($key, static::ADDITIVITY_PREFIX) === true) {
                $name = substr($key, strlen(static::ADDITIVITY_PREFIX));
                $this->config['loggers'][$name]['additivity'] = $value;
            
            } elseif ($this->beginsWith($key, static::APPENDER_PREFIX) === true) {
                // Parse appenders.
                $this->parseAppender($key, $value);
            
            } elseif ($this->beginsWith($key, static::RENDERER_PREFIX) === true) {
                // Parse renderers.
                $this->parseRenderer($key, $value);
            }//end if
        }//end foreach
        
        return $this->config;
        
    }//end convert()
    
    
    /**
     * Parses a logger definition.
     * 
     * Loggers are defined in the following manner:
     * <pre>
     * log4php.logger.<name> = [<level>], [<appender-ref>, <appender-ref>, ...] 
     * </pre>
     * 
     * @param string $value The configuration value (level and appender-refs).
     * @param string $name  Logger name.
     * 
     * @return void
     * 
     * @access private
     */
    private function parseLogger($value, $name)
    {
        // Value is divided by commas.
        $parts = explode(',', $value);
        
        if ((empty($value) === true) || (empty($parts) === true)) {
            return;
        }//end if

        // The first value is the logger level. 
        $level = array_shift($parts);
        
        // The remaining values are appender references. 
        $appenders = array();
        
        while ($appender = array_shift($parts)) {
            $appender = trim($appender);
            if (empty($appender) === false) {
                $appenders[] = trim($appender);
            }//end if
        }//end while

        // Find the target configuration. 
        if ($name === static::ROOT_LOGGER_NAME) {
            $this->config['rootLogger']['level'] = trim($level);
            $this->config['rootLogger']['appenders'] = $appenders;
        } else {
            $this->config['loggers'][$name]['level'] = trim($level);
            $this->config['loggers'][$name]['appenders'] = $appenders;
        }//end if
        
    }//end parseLogger()
    
    
    /**
     * Parses an configuration line pertaining to an appender.
     * 
     * Parses the following patterns:
     * 
     * Appender class:
     * <pre>
     * log4php.appender.<name> = <class>
     * </pre>
     * 
     * Appender parameter:
     * <pre>
     * log4php.appender.<name>.<param> = <value>
     * </pre>
     * 
     * Appender threshold:
     * <pre>
     * log4php.appender.<name>.threshold = <level>
     * </pre>
     * 
     * Appender layout:
     * <pre>
     * log4php.appender.<name>.layout = <layoutClass>
     * </pre>
     * 
     * Layout parameter:
     * <pre>
     * log4php.appender.<name>.layout.<param> = <value>
     * </pre> 
     * 
     * For example, a full appender config might look like:
     * <pre>
     * log4php.appender.myAppender = LoggerAppenderConsole
     * log4php.appender.myAppender.threshold = info
     * log4php.appender.myAppender.target = stdout
     * log4php.appender.myAppender.layout = LoggerLayoutPattern
     * log4php.appender.myAppender.layout.conversionPattern = "%d %c: %m%n"
     * </pre>
     * 
     * After parsing all these options, the following configuration can be 
     * found under $this->config['appenders']['myAppender']:
     * <pre>
     * array(
     *     'class' => LoggerAppenderConsole,
     *     'threshold' => info,
     *     'params' => array(
     *         'target' => 'stdout'
     *     ),
     *     'layout' => array(
     *         'class' => 'LoggerAppenderConsole',
     *         'params' => array(
     *             'conversionPattern' => '%d %c: %m%n'
     *         )
     *     )
     * )
     * </pre>
     * 
     * @param string $key   Key.
     * @param string $value Value.
     * 
     * @return void
     * 
     * @access private
     */
    private function parseAppender($key, $value)
    {

        // Remove the appender prefix from key.
        $subKey = substr($key, strlen(static::APPENDER_PREFIX));
        
        // Divide the string by dots.
        $parts = explode('.', $subKey);
        $count = count($parts);
        
        // The first part is always the appender name.
        $name = trim($parts[0]);
        
        // Only one part - this line defines the appender class. 
        if ($count === 1) {
            $this->config['appenders'][$name]['class'] = $value;
            return;
            
        } elseif ($count === 2) {
            
            // Two parts - either a parameter, a threshold or layout class.
            if ($parts[1] === 'layout') {
                $this->config['appenders'][$name]['layout']['class'] = $value;
                return;
            } elseif ($parts[1] === 'threshold') {
                $this->config['appenders'][$name]['threshold'] = $value;
                return;
            } else {
                $this->config['appenders'][$name]['params'][$parts[1]] = $value;
                return;
            }//end if
        
        } elseif ($count === 3) {
            // Three parts - this can only be a layout parameter.
            if ($parts[1] === 'layout') {
                $this->config['appenders'][$name]['layout']['params'][$parts[2]] = $value;
                return;
            }//end if
        }//end if
        
        trigger_error('log4php: Don\'t know how to parse the following line: \''.$key.' = '.$value.'\'. Skipping.');
        
    }//end parseAppender()
    

    /**
     * Parses a renderer definition.
     * 
     * Renderers are defined as:
     * <pre>
     * log4php.renderer.<renderedClass> = <renderingClass> 
     * </pre>
     * 
     * @param string $key   Log4php.renderer.<renderedClass>.
     * @param string $value RenderingClass.
     * 
     * @return void
     * 
     * @access private
     */
    private function parseRenderer($key, $value)
    {
        // Remove the appender prefix from key.
        $renderedClass  = substr($key, strlen(static::APPENDER_PREFIX));
        $renderingClass = $value;
        
        $this->config['renderers'][] = compact('renderedClass', 'renderingClass');
        
    }//end parseRenderer()
    
    
    /**
     * Helper method. Returns true if $str begins with $sub.
     * 
     * @param string $str String.
     * @param string $sub Sub.
     * 
     * @return boolean
     * 
     * @access private
     */
    private function beginsWith($str, $sub)
    {
        if (strncmp($str, $sub, strlen($sub)) === 0) {
            $out = true;
        } else {
            $out = false;
        }//end if
        
        return $out;
        
    }//end beginsWith()
    
    
}//end LoggerConfigurationAdapterINI class
