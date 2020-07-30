<?php
/**
 * LoggerConfigurationAdapterPHP.
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
 * PHP Version 5
 * 
 * @package    Log4php
 * @subpackage Configurators
 */

namespace log4php\configurators;

use log4php\LoggerException;

/**
 * Converts PHP configuration files to a PHP array.
 * 
 * The file should only hold the PHP config array preceded by "return".
 * 
 * Example PHP config file:
 * <code>
 * <?php
 * return array(
 *   'rootLogger' => array(
 *     'level' => 'info',
 *     'appenders' => array('default')
 *   ),
 *   'appenders' => array(
 *     'default' => array(
 *       'class' => 'LoggerAppenderEcho',
 *       'layout' => array(
 *           'class' => 'LoggerLayoutSimple'
 *        )
 *     )
 *   )
 * )
 * 
 * </code>
 */
class LoggerConfigurationAdapterPHP implements LoggerConfigurationAdapter
{
    
    
    /**
     * Convert.
     * 
     * @param string $url Url.
     * 
     * @return array
     * 
     * @access public
     * 
     * @throws LoggerException
     */
    public function convert($url)
    {
        if (file_exists($url) === false) {
            throw new LoggerException('File ['.$url.'] does not exist.');
        }//end if
        
        // Load the config file.
        $data = @file_get_contents($url);
        
        if ($data === false) {
            $error = error_get_last();
            throw new LoggerException('Error loading config file: '.$error['message']);
        }//end if
        
        $config = @eval('' . $data);
        
        if ($config === false) {
            $error = error_get_last();
            throw new LoggerException('Error parsing configuration: '.$error['message']);
        }//end if
        
        if (empty($config) === true) {
            throw new LoggerException('Invalid configuration: empty configuration array.');
        }//end if
        
        if (is_array($config) === false) {
            throw new LoggerException('Invalid configuration: not an array.');
        }//end if
        
        return $config;
        
    }//end convert()
    

}//end LoggerConfigurationAdapterPHP class
