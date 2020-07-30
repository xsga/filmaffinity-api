<?php
/**
 * LoggerLayout.
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
 * @package Log4php
 */

namespace log4php;

/**
 * Extend this abstract class to create your own log layout format.
 */
abstract class LoggerLayout extends LoggerConfigurable
{
    
    
    /**
     * Activates options for this layout. Override this method if you have options to be activated.
     * 
     * @return boolean
     * 
     * @access public
     */
    public function activateOptions()
    {
        return true;
        
    }//end activateOptions()
    
    
    /**
     * Override this method to create your own layout format.
     *
     * @param LoggerLoggingEvent $event Loggin event.
     * 
     * @return string
     * 
     * @access public
     */
    public function format(LoggerLoggingEvent $event)
    {
        
        return $event->getRenderedMessage();
        
    }//end format()
    
    
    /**
     * Returns the content type output by this layout.
     * 
     * @return string
     * 
     * @access public
     */
    public function getContentType()
    {
        return 'text/plain';
        
    }//end getContentType()
    
    
    /**
     * Returns the footer for the layout format.
     * 
     * @return string
     * 
     * @access public
     */
    public function getFooter()
    {
        return null;
        
    }//end getFooter()
    
    
    /**
     * Returns the header for the layout format.
     * 
     * @return string
     * 
     * @access public
     */
    public function getHeader()
    {
        return null;
        
    }//end getHeader()
    
    
    /**
     * Triggers a warning for this layout with the given message.
     * 
     * @param string $message Message.
     * 
     * @return void
     * 
     * @access protected
     */
    protected function warn($message)
    {
        trigger_error('log4php: ['.get_class($this).']: '.$message, E_USER_WARNING);
        
    }//end warn()
    
    
}//end LoggerLayout class
