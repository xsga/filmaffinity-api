<?php
/**
 * LoggerUtils.
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
 * @subpackage Helpers
 */

namespace log4php\helpers;

/**
 * Contains various helper methods.
 */
class LoggerUtils
{
    
    
    /**
     * Splits a fully qualified class name into fragments delimited by the namespace separator (\). 
     * 
     * For backward compatibility, a dot (.) can be used as a delimiter as well. 
     * 
     * @param string $name Name.
     * 
     * @return array Class name split into fragments.
     * 
     * @access public
     */
    public static function tokenizeClassName($name)
    {
        $name      = str_replace('.', '\\', $name);
        $name      = trim($name, ' \\');
        $fragments = explode('\\', $name);
        
        foreach ($fragments as $key => $fragment) {
            if (trim($fragment) === '') {
                unset($fragments[$key]);
            }//end if
        }//end foreach
        
        return $fragments;
        
    }//end tokenizeClassName()
    
    
    /**
     * Attempts to shorten the given class name to the desired length.
     * 
     * This is done by separating the class name into fragments (delimited by \ or .) and trimming individual fragments,
     * starting with the left, until desired length has been reached. 
     * 
     * The final fragment (i.e. class name) will never be shortened so the result may still be longer than given length.
     * 
     * @param string  $name   The (qualified) class name.
     * @param integer $length The length to shorten to. If null or 0, the name will be returned without shortening.
     * 
     * @return string
     * 
     * @access public
     */
    public static function shortenClassName($name, $length)
    {
        if (($length === null) || ($length < 0)) {
            return $name;
        }//end if
        
        $name = str_replace('.', '\\', $name);
        $name = trim($name, ' \\');
        
        // Check if any shortening is required.
        $currentLength = strlen($name);
        if ($currentLength <= $length) {
            return $name;
        }//end if
    
        // Split name into fragments.
        $fragments = explode('\\', $name);

        // If zero length is specified, return only last fragment.
        if ($length === 0) {
            return array_pop($fragments);
        }//end if
        
        // If the name splits to only one fragment, then it cannot be shortened.
        $count = count($fragments);
        if ($count === 1) {
            return $name;
        }//end if
    
        foreach ($fragments as $key => &$fragment) {
    
            // Never shorten last fragment.
            if ($key === ($count - 1)) {
                break;
            }//end if
    
            // Check for empty fragments (shouldn't happen but it's possible).
            $fragLen = strlen($fragment);
            if ($fragLen <= 1) {
                continue;
            }//end if
    
            // Shorten fragment to one character and check if total length satisfactory.
            $fragment      = substr($fragment, 0, 1);
            $currentLength = ($currentLength - $fragLen + 1);
    
            if ($currentLength <= $length) {
                break;
            }//end if
            
        }//end foreach
        
        unset($fragment);
        
        return implode('\\', $fragments);
        
    }//end shortenClassName()
    
    
}//end LoggerUtils class
