<?php
/**
 * LoggerReflectionUtils.
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
 * Provides methods for reflective use on php objects.
 */
class LoggerReflectionUtils
{
    
    /**
     * The target object.
     * 
     * @var mixed
     * 
     * @access private
     */
    private $obj;
    
    
    /**
     * Create a new LoggerReflectionUtils for the specified Object.
     * 
     * @param object $obj The object for which to set properties.
     * 
     * @access public
     */
    public function __construct($obj)
    {
        $this->obj = $obj;
        
    }//end __construct()
    
    
    /**
     * Set the properties of an object passed as a parameter in one go. The properties are parsed relative to a prefix.
     *
     * @param object $obj        The object to configure.
     * @param array  $properties An array containing keys and values.
     * @param string $prefix     Only keys having the specified prefix will be set.
     * 
     * @return mixed
     */
    public static function setPropertiesByObject($obj, array $properties, $prefix) : mixed
    {
        $pSetter = new LoggerReflectionUtils($obj);
        
        return $pSetter->setProperties($properties, $prefix);
        
    }//end setPropertiesByObject()
    
    
    /**
     * Set the properites for the object that match the prefix passed as parameter.
     * 
     * @param array  $properties An array containing keys and values.
     * @param string $prefix     Only keys having the specified prefix will be set.
     * 
     * @return void
     * 
     * @access public
     */
    public function setProperties(array $properties, $prefix) : void
    {
        $len = strlen($prefix);
        
        reset($properties);
        
        foreach($properties as $key => $value) {
            
            if (strpos($key, $prefix) === 0) {
                
                if (strpos($key, '.', ($len + 1)) > 0) {
                    continue;
                }//end if
                
                $value = $properties[$key];
                $key   = substr($key, $len);
                
                if (($key === 'layout') && ($this->obj instanceof LoggerAppender)) {
                    continue;
                }//end if
                
                $this->setProperty($key, $value);
                
            }//end if
            
        }//end while
        
        $this->activate();
        
    }//end setProperties()
    
    
    /**
     * Function setProperty.
     * 
     * Set a property on this PropertySetter's Object. If successful, this method will invoke a setter method on 
     * the underlying Object. The setter is the one for the specified property name and the value is determined partly 
     * from the setter argument type and partly from the value specified in the call to this method.
     *
     * If the setter expects a String no conversion is necessary.
     * If it expects an int, then an attempt is made to convert 'value' to an int using new Integer(value).
     * If the setter expects a boolean, the conversion is by new Boolean(value).
     *
     * @param string $name  Name of the property.
     * @param string $value String value of the property.
     * 
     * @return mixed
     * 
     * @access public
     * 
     * @throws \RuntimeException
     */
    public function setProperty($name, $value) : mixed
    {
        if ($value === null) {
            return null;
        }//end if
        
        $method = 'set'.ucfirst($name);
        
        if (!method_exists($this->obj, $method)) {
            
            $msg  = 'Error setting log4php property '.$name.' to '.$value.': no method '.$method.' in class ';
            $msg .= get_class($this->obj);
            
            throw new \RuntimeException($msg);
            
        } else {
            
            return call_user_func(array($this->obj, $method), $value);
            
        }//end if
        
    }//end setProperty()
    
    
    /**
     * Activate.
     * 
     * @return mixed
     * 
     * @access public
     */
    public function activate() : mixed
    {
        if (method_exists($this->obj, 'activateoptions')) {
            return call_user_func(array($this->obj, 'activateoptions'));
        }//end if
        
    }//end activate()
    
    
    /**
     * Creates an instances from the given class name.
     *
     * @param string $classname Class name.
     * 
     * @return mixed
     * 
     * @access public
     */
    public static function createObject($class) : mixed
    {
        if (!empty($class)) {
            return new $class();
        }//end if
        
        return null;
        
    }//end createObject()
    
    
    /**
     * Setter.
     * 
     * @param object $object Object.
     * @param string $name   Name.
     * @param mixed  $value  Value.
     * 
     * @return mixed
     * 
     * @access public
     */
    public static function setter($object, $name, $value) : mixed
    {
        if (empty($name)) {
            return false;
        }//end if
        
        $methodName = 'set'.ucfirst($name);
        
        if (method_exists($object, $methodName)) {
            return call_user_func(array($object, $methodName), $value);
        } else {
            return false;
        }//end if
        
    }//end setter()
    
    
}//end LoggerReflectionUtils class
