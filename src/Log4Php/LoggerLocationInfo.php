<?php

/**
 * LoggerLocationInfo.
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
 * @package Log4Php
 */

/**
 * Namespace.
 */
namespace Log4Php;

/**
 * The internal representation of caller location information.
 */
class LoggerLocationInfo
{
    /**
     * The value to return when the location information is not available.
     *
     * @var string
     *
     * @access public
     */
    public const LOCATION_INFO_NA = 'NA';

    /**
     * Caller line number.
     *
     * @var integer
     *
     * @access protected
     */
    protected $lineNumber;

    /**
     * Caller file name.
     *
     * @var string
     *
     * @access protected
     */
    protected $fileName;

    /**
     * Caller class name.
     *
     * @var string
     *
     * @access protected
     */
    protected $className;

    /**
     * Caller method name.
     *
     * @var string
     *
     * @access protected
     */
    protected $methodName;

    /**
     * All the information combined.
     *
     * @var string
     *
     * @access protected
     */
    protected $fullInfo = '';

    /**
     * Instantiate location information based on a {@link PHP_MANUAL#debug_backtrace}.
     *
     * @param array $trace Trace.
     *
     * @access public
     */
    public function __construct(array $trace)
    {
        if (isset($trace['line'])) {
            $this->lineNumber = $trace['line'];
        } else {
            $this->lineNumber = 0;
        }//end if

        if (isset($trace['file'])) {
            $this->fileName = $trace['file'];
        } else {
            $this->fileName = '';
        }//end if

        if (isset($trace['class'])) {
            $this->className = $trace['class'];
        } else {
            $this->className = '';
        }//end if

        if (isset($trace['function'])) {
            $this->methodName = $trace['function'];
        } else {
            $this->methodName = '';
        }//end if

        $this->fullInfo  = $this->getClassName() . '.' . $this->getMethodName();
        $this->fullInfo .= '(' . $this->getFileName() . ':' . $this->getLineNumber() . ')';
    }

    /**
     * Returns the caller class name.
     *
     * @return string
     *
     * @access public
     */
    public function getClassName(): string
    {
        if (empty($this->className)) {
            return static::LOCATION_INFO_NA;
        }//end if

        return $this->className;
    }

    /**
     * Returns the caller file name.
     *
     * @return string
     *
     * @access public
     */
    public function getFileName(): string
    {
        if (empty($this->fileName)) {
            return static::LOCATION_INFO_NA;
        }//end if

        return $this->fileName;
    }

    /**
     * Returns the caller line number.
     *
     * @return integer|string
     *
     * @access public
     */
    public function getLineNumber(): int|string
    {
        if ($this->lineNumber === 0) {
            return static::LOCATION_INFO_NA;
        }//end if

        return $this->lineNumber;
    }

    /**
     * Returns the caller method name.
     *
     * @return string
     *
     * @access public
     */
    public function getMethodName(): string
    {
        if (empty($this->methodName)) {
            return static::LOCATION_INFO_NA;
        }//end if

        return $this->methodName;
    }

    /**
     * Returns the full information of the caller.
     *
     * @return string
     *
     * @access public
     */
    public function getFullInfo(): string
    {
        if (empty($this->fullInfo)) {
            return static::LOCATION_INFO_NA;
        }//end if

        return $this->fullInfo;
    }
}
