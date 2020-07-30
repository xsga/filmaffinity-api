<?php
/**
 * LoggerHierarchy.
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

use log4php\renderers\LoggerRendererMap;

/**
 * LoggerHierarchy.
 * 
 * This class is specialized in retrieving loggers by name and also maintaining 
 * the logger hierarchy. The logger hierarchy is dealing with the several Log-Levels
 * Logger can have. From log4j website:
 * 
 * A logger is said to be an ancestor of another logger if its name followed 
 * by a dot is a prefix of the descendant logger name. A logger is said to be
 * a parent of a child logger if there are no ancestors between itself and the 
 * descendant logger.
 * 
 * Child Loggers do inherit their Log-Levels from their Ancestors. They can
 * increase their Log-Level compared to their Ancestors, but they cannot decrease it.
 * 
 * The casual user does not have to deal with this class directly.
 *
 * The structure of the logger hierarchy is maintained by the
 * getLogger method. The hierarchy is such that children link
 * to their parent but parents do not have any pointers to their
 * children. Moreover, loggers can be instantiated in any order, in
 * particular descendant before ancestor.
 *
 * In case a descendant is created before a particular ancestor,
 * then it creates a provision node for the ancestor and adds itself
 * to the provision node. Other descendants of the same ancestor add
 * themselves to the previously created provision node.
 */
class LoggerHierarchy
{
    
    /**
     * Array holding all Logger instances.
     * 
     * @var array
     * 
     * @access protected
     */
    protected $loggers = array();
    
    /**
     * The root logger.
     * 
     * @var LoggerRoot
     * 
     * @access protected
     */
    protected $root;
    
    /**
     * The logger renderer map.
     * 
     * @var LoggerRendererMap
     * 
     * @access protected
     */
    protected $rendererMap;
    
    /**
     * Main level threshold. Events with lower level will not be logged by any logger, regardless of it's configuration.
     * 
     * @var LoggerLevel
     * 
     * @access protected
     */
    protected $threshold;
    
    
    /**
     * Creates a new logger hierarchy.
     * 
     * @param LoggerRoot $root The root logger.
     * 
     * @access public
     */
    public function __construct(LoggerRoot $root)
    {
        $this->root = $root;
        $this->setThreshold(LoggerLevel::getLevelAll());
        $this->rendererMap = new LoggerRendererMap();
        
    }//end __construct()
    
    
    /**
     * Clears all loggers.
     * 
     * @return void
     * 
     * @access public
     */
    public function clear()
    {
        $this->loggers = array();
        
    }//end clear()
    
    
    /**
     * Check if the named logger exists in the hierarchy.
     * 
     * @param string $name Name.
     * 
     * @return boolean
     * 
     * @access public
     */
    public function exists($name)
    {
        return isset($this->loggers[$name]);
        
    }//end exists()
    
    
    /**
     * Returns all the currently defined loggers in this hierarchy as an array.
     * 
     * @return array
     * 
     * @access public
     */
    public function getCurrentLoggers()
    {
        
        return array_values($this->loggers);
        
    }//end getCurrentLoggers()
    
    
    /**
     * Returns a named logger instance logger. If it doesn't exist, one is created.
     * 
     * @param string $name Logger name.
     * 
     * @return Logger
     * 
     * @access public
     */
    public function getLogger($name)
    {
        if (isset($this->loggers[$name]) === false) {
            
            $logger    = new Logger($name);
            $nodes     = explode('.', $name);
            $firstNode = array_shift($nodes);
            
            // If name is not a first node but another first node is their.
            if (($firstNode !== $name) && (isset($this->loggers[$firstNode]) === true)) {
                $logger->setParent($this->loggers[$firstNode]);
            } else {
                // If there is no father, set root logger as father.
                $logger->setParent($this->root);
            }//end if
            
            // If there are more nodes than one.
            if (count($nodes) > 0) {
                // Find parent node.
                foreach ($nodes as $node) {
                    
                    $parentNode = $firstNode.$node;
                    
                    if ((isset($this->loggers[$parentNode]) === true) && ($parentNode !== $name)) {
                        $logger->setParent($this->loggers[$parentNode]);
                    }//end if
                    
                    $firstNode .= $node;
                }//end foreach
                
            }//end if
            
            $this->loggers[$name] = $logger;
            
        }//end if
        
        return $this->loggers[$name];
        
    }//end getLogger()
    
    
    /**
     * Returns the logger renderer map.
     * 
     * @return LoggerRendererMap
     * 
     * @access public
     */
    public function getRendererMap()
    {
        return $this->rendererMap;
        
    }//end getRendererMap()
    
    
    /**
     * Returns the root logger.
     * 
     * @return LoggerRoot
     * 
     * @access public
     */
    public function getRootLogger()
    {
        return $this->root;
        
    }//end getRootLogger()
    
     
    /**
     * Returns the main threshold level.
     * 
     * @return LoggerLevel
     * 
     * @access public
     */
    public function getThreshold()
    {
        return $this->threshold;
        
    }//end getThreshold()
    
    
    /**
     * Returns true if the hierarchy is disabled for given log level and false otherwise.
     * 
     * @param LoggerLevel $level Logger level.
     * 
     * @return boolean
     * 
     * @access public
     */
    public function isDisabled(LoggerLevel $level)
    {
        if ($this->threshold->toInt() > $level->toInt()) {
            $out = true;
        } else {
            $out = false;
        }//end if
        
        return $out;
        
    }//end isDisabled()
    
    
    /**
     * Reset all values contained in this hierarchy instance to their default. 
     *
     * This removes all appenders from all loggers, sets
     * the level of all non-root loggers to <i>null</i>,
     * sets their additivity flag to <i>true</i> and sets the level
     * of the root logger to {@link LOGGER_LEVEL_DEBUG}.
     * 
     * Existing loggers are not removed. They are just reset.
     *
     * This method should be used sparingly and with care as it will block all logging until it is completed.
     * 
     * @return void
     * 
     * @access public
     */
    public function resetConfiguration()
    {
        $root = $this->getRootLogger();
        
        $root->setLevel(LoggerLevel::getLevelDebug());
        $this->setThreshold(LoggerLevel::getLevelAll());
        $this->shutDown();
        
        foreach ($this->loggers as $logger) {
            $logger->setLevel(null);
            $logger->setAdditivity(true);
            $logger->removeAllAppenders();
        }//end foreach
        
        $this->rendererMap->reset();
        LoggerAppenderPool::clear();
        
    }//end resetConfiguration()
    
    
    /**
     * Sets the main threshold level.
     * 
     * @param LoggerLevel $l Logger level.
     * 
     * @return void
     * 
     * @access public
     */
    public function setThreshold(LoggerLevel $threshold)
    {
        $this->threshold = $threshold;
        
    }//end setThreshold()
    
    
    /**
     * Shutting down a hierarchy will safely close and remove all appenders in all loggers including the root logger.
     * 
     * The shutdown method is careful to close nested appenders before closing regular appenders. This is allows
     * configurations where a regular appender is attached to a logger and again to a nested appender.
     * 
     * @return void
     * 
     * @access public
     */
    public function shutdown()
    {
        $this->root->removeAllAppenders();
        
        foreach ($this->loggers as $logger) {
            $logger->removeAllAppenders();
        }//end foreach
        
    }//end shutdown()
    
    
    /**
     * Prints the current Logger hierarchy tree. Useful for debugging.
     * 
     * @return void
     * 
     * @access public
     */
    public function printHierarchy()
    {
        $this->printHierarchyInner($this->getRootLogger(), 0);
        
    }//end printHierarchy()
    
    
    /**
     * Print hierarchy inner.
     * 
     * @param Logger  $current Current logger.
     * @param integer $level   Level.
     * 
     * @return void
     * 
     * @access private
     */
    private function printHierarchyInner(Logger $current, $level)
    {
        for ($i = 0; $i < $level; $i++) {
            echo ($i === $level - 1) ? '|--' : '|  ';
        }//end for
        
        echo $current->getName() . "\n";
        
        foreach ($this->loggers as $logger) {
            if ($logger->getParent() === $current) {
                $this->printHierarchyInner($logger, ($level + 1));
            }//end if
        }//end foreach
        
    }//end printHierarchyInner()
    
    
}//end LoggerHierarchy class
