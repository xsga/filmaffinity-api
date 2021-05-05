<?php
/**
 * LoggerFilter.
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
 * LoggerFilter.
 * 
 * Users should extend this class to implement customized logging
 * event filtering. Note that {@link LoggerCategory} and {@link LoggerAppender}, 
 * the parent class of all standard
 * appenders, have built-in filtering rules. It is suggested that you
 * first use and understand the built-in rules before rushing to write
 * your own custom filters.
 * 
 * This abstract class assumes and also imposes that filters be
 * organized in a linear chain. The {@link #decide
 * decide(LoggerLoggingEvent)} method of each filter is called sequentially,
 * in the order of their addition to the chain.
 * 
 * The {@link decide()} method must return one
 * of the integer constants {@link LoggerFilter::DENY}, 
 * {@link LoggerFilter::NEUTRAL} or {@link LoggerFilter::ACCEPT}.
 * 
 * If the value {@link LoggerFilter::DENY} is returned, then the log event is
 * dropped immediately without consulting with the remaining
 * filters.
 * 
 * If the value {@link LoggerFilter::NEUTRAL} is returned, then the next filter
 * in the chain is consulted. If there are no more filters in the
 * chain, then the log event is logged. Thus, in the presence of no
 * filters, the default behaviour is to log all logging events.
 * 
 * If the value {@link LoggerFilter::ACCEPT} is returned, then the log
 * event is logged without consulting the remaining filters.
 * 
 * The philosophy of log4php filters is largely inspired from the
 * Linux ipchains.
 */
abstract class LoggerFilter extends LoggerConfigurable
{
    
    /**
     * The log event must be logged immediately without consulting with the remaining filters, if any, in the chain.
     * 
     * @var integer
     * 
     * @access public
     */
    const ACCEPT = 1;
    
    /**
     * This filter is neutral with respect to the log event.
     * The remaining filters, if any, should be consulted for a final decision.
     * 
     * @var integer
     * 
     * @access public
     */
    const NEUTRAL = 0;
    
    /**
     * The log event must be dropped immediately without consulting with the remaining filters, if any, in the chain.
     * 
     * @var integer
     * 
     * @access public
     */
    const DENY = -1;
    
    /**
     * Points to the next {@link LoggerFilter} in the filter chain.
     * 
     * @var LoggerFilter
     * 
     * @access protected
     */
    protected $next;
    
    
    /**
     * Usually filters options become active when set. We provide a default do-nothing implementation for convenience.
     * 
     * @return void
     * 
     * @access public
     */
    public function activateOptions() : void
    {
                
    }//end activateOptions()
    
    
    /**
     * Decide what to do.
     * 
     * If the decision is {@link LoggerFilter::DENY}, then the event will be
     * dropped. If the decision is {@link LoggerFilter::NEUTRAL}, then the next
     * filter, if any, will be invoked. If the decision is {@link LoggerFilter::ACCEPT} then
     * the event will be logged without consulting with other filters in
     * the chain.
     *
     * @param LoggerLoggingEvent $event The {@link LoggerLoggingEvent} to decide upon.
     * 
     * @return integer {@link LoggerFilter::NEUTRAL} or {@link LoggerFilter::DENY}|{@link LoggerFilter::ACCEPT}
     * 
     * @access public
     */
    public function decide(LoggerLoggingEvent $event) : int
    {
        return static::NEUTRAL;
        
    }//end decide()
    
    
    /**
     * Add next.
     * 
     * Adds a new filter to the filter chain this filter is a part of.
     * If this filter has already and follow up filter, the param filter 
     * is passed on until it is the last filter in chain.
     * 
     * @param mixed $filter The filter to add to this chain.
     * 
     * @return void
     * 
     * @access public
     */
    public function addNext($filter) : void
    {
        if ($this->next !== null) {
            $this->next->addNext($filter);
        } else {
            $this->next = $filter;
        }//end if
        
    }//end addNext()
    
    
    /**
     * Returns the next filter in this chain.
     * 
     * @return mixed
     * 
     * @access public
     */
    public function getNext() : mixed
    {
        return $this->next;
        
    }//end getNext()
    
    
}//end LoggerFilter class
