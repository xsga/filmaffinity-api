<?php
/**
 * LoggerPatternConverterEnvironment.
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
 * @package    Log4php
 * @subpackage Pattern
 */

/**
 * Namespace.
 */
namespace log4php\pattern;

/**
 * Returns a value from the $_ENV superglobal array corresponding to the given key.
 * 
 * Options:
 *  [0] $_ENV key value
 */
class LoggerPatternConverterEnvironment extends LoggerPatternConverterSuperglobal
{
    
    /**
     * Name.
     * 
     * @var string
     * 
     * @access protected
     */
    protected $name = '_ENV';
    
}//end LoggerPatternConverterEnvironment class
