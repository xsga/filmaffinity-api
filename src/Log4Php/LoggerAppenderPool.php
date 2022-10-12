<?php

/**
 * LoggerAppenderPool.
 *
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements. See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
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
 * Pool implmentation for LoggerAppender instances.
 *
 * The pool is used when configuring log4php. First all appender instances
 * are created in the pool. Afterward, they are linked to loggers, each
 * appender can be linked to multiple loggers. This makes sure duplicate
 * appenders are not created.
 */
class LoggerAppenderPool
{
    /**
     * Holds appenders indexed by their name.
     *
     * @var array
     *
     * @access public
     */
    public static $appenders = array();

    /**
     * Adds an appender to the pool. The appender must be named for this operation.
     *
     * @param LoggerAppender $appender Logger appender.
     *
     * @return void
     *
     * @access public
     */
    public static function add(LoggerAppender $appender): void
    {
        $name = $appender->getName();

        if (empty($name)) {
            trigger_error('log4php: Cannot add unnamed appender to pool.', E_USER_WARNING);
            return;
        }//end if

        if (isset(static::$appenders[$name])) {
            $log = "log4php: Appender [$name] already exists in pool. Overwriting existing appender.";
            trigger_error($log, E_USER_WARNING);
        }//end if

        static::$appenders[$name] = $appender;
    }

    /**
     * Retrieves an appender from the pool by name.
     *
     * @param string $name Name of the appender to retrieve.
     *
     * @return LoggerAppender|null The named appender or null if no such appender exists in the pool.
     *
     * @access public
     */
    public static function get(string $name): LoggerAppender|null
    {
        return (isset(static::$appenders[$name])) ? static::$appenders[$name] : null;
    }

    /**
     * Removes an appender from the pool by name.
     *
     * @param string $name Name of the appender to remove.
     *
     * @return void
     *
     * @access public
     */
    public static function delete(string $name): void
    {
        unset(static::$appenders[$name]);
    }

    /**
     * Returns all appenders from the pool.
     *
     * @return array Array of LoggerAppender objects.
     *
     * @access public
     */
    public static function getAppenders(): array
    {
        return static::$appenders;
    }

    /**
     * Checks whether an appender exists in the pool.
     *
     * @param string $name Name of the appender to look for.
     *
     * @return boolean true if the appender with the given name exists.
     *
     * @access public
     */
    public static function exists(string $name): bool
    {
        return isset(static::$appenders[$name]);
    }

    /**
     * Clears all appenders from the pool.
     *
     * @return void
     *
     * @access public
     */
    public static function clear(): void
    {
         static::$appenders = array();
    }
}
