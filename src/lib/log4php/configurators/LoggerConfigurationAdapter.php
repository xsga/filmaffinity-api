<?php
/**
 * LoggerConfigurationAdapter.
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
 * PHP Version 8
 * 
 * @package    Log4php
 * @subpackage Configurators
 */

/**
 * Namespace.
 */
namespace log4php\configurators;

/**
 * The interface for configurator adapters.
 * 
 * Adapters convert configuration in several formats such as XML, ini and PHP file to a PHP array. 
 */
interface LoggerConfigurationAdapter
{
    
    
    /**
     * Converts the configuration file to PHP format usable by the configurator.
     * 
     * @param string $input Input.
     * 
     * @access public
     * 
     * @return array
     */
    public function convert($input); 

    
}//end LoggerConfigurationAdapter interface
