<?php
/**
 * LoggerMDC.
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
 * @package Log4php
 */

/**
 * Namespace.
 */
namespace log4php;

/**
 * The LoggerMDC class provides _mapped diagnostic contexts_.
 * 
 * A Mapped Diagnostic Context, or MDC in short, is an instrument for 
 * distinguishing interleaved log output from different sources. Log output 
 * is typically interleaved when a server handles multiple clients 
 * near-simultaneously.
 * 
 * This class is similar to the {@link LoggerNDC} class except that 
 * it is based on a map instead of a stack.
 */
class LoggerMDC
{
    
    /**
     * Holds the context map.
     * 
     * @var array
     * 
     * @access private
     */
    private static $map = array();
    
    
    /**
     * Stores a context value as identified with the key parameter into the context map.
     *
     * @param string $key   The key.
     * @param string $value The value.
     * 
     * @return void
     * 
     * @access public
     */
    public static function put($key, $value) : void
    {
        static::$map[$key] = $value;
        
    }//end put()
    
    
    /**
     * Returns the context value identified by the key parameter.
     *
     * @param string $key The key.
     * 
     * @return string The context or an empty string if no context found for given key.
     * 
     * @access public
     */
    public static function get($key) : string
    {
        if (isset(static::$map[$key])) {
            $out = static::$map[$key];
            
        } else {
            $out = '';
            
        }//end if
        
        return $out;
        
    }//end get()
    
    
    /**
     * Returns the contex map as an array.
     * 
     * @return array The MDC context map.
     * 
     * @access public
     */
    public static function getMap() : array
    {
        return static::$map;
        
    }//end getMap()
    
    
    /**
     * Removes the the context identified by the key parameter. Only affects user mappings, not $_ENV or $_SERVER.
     *
     * @param string $key The key to be removed.
     * 
     * @return void
     * 
     * @access public
     */
    public static function remove($key) : void
    {
        unset(static::$map[$key]);
        
    }//end remove()
    
    
    /**
     * Clears the mapped diagnostic context.
     * 
     * @return void
     * 
     * @access public
     */
    public static function clear() : void
    {
        static::$map = array();
        
    }//end clear()
    
    
}//end LoggerMDC class
