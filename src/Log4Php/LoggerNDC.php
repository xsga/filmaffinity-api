<?php

/**
 * LoggerNDC.
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
 * The NDC class implements <i>nested diagnostic contexts</i>.
 *
 * NDC was defined by Neil Harrison in the article "Patterns for Logging
 * Diagnostic Messages" part of the book <i>"Pattern Languages of
 * Program Design 3"</i> edited by Martin et al.
 *
 * A Nested Diagnostic Context, or NDC in short, is an instrument
 * to distinguish interleaved log output from different sources. Log
 * output is typically interleaved when a server handles multiple
 * clients near-simultaneously.
 *
 * This class is similar to the {@link LoggerMDC} class except that it is
 * based on a stack instead of a map.
 *
 * Interleaved log output can still be meaningful if each log entry
 * from different contexts had a distinctive stamp. This is where NDCs
 * come into play.
 */
class LoggerNDC
{
    /**
     * This is the repository of NDC stack.
     *
     * @var array
     *
     * @access private
     */
    private static $stack = array();

    /**
     * Clear.
     *
     * Clear any nested diagnostic information if any. This method is useful in cases where the same thread can be
     * potentially used over and over in different unrelated contexts.
     *
     * @return void
     *
     * @access public
     */
    public static function clear(): void
    {
        static::$stack = array();
    }

    /**
     * Never use this method directly, use the {@link LoggerLoggingEvent::getNDC()} method instead.
     *
     * @return string
     *
     * @access public
     */
    public static function get(): string
    {
        return implode(' ', static::$stack);
    }

    /**
     * Get the current nesting depth of this diagnostic context.
     *
     * @return integer
     *
     * @access public
     */
    public static function getDepth(): int
    {
        return count(static::$stack);
    }

    /**
     * Clients should call this method before leaving a diagnostic context.
     *
     * @return string The innermost diagnostic context.
     *
     * @access public
     */
    public static function pop(): string
    {
        $count = (static::$stack);

        if ($count > 0) {
            return array_pop(static::$stack);
        }//end if

        return '';
    }

    /**
     * Looks at the last diagnostic context at the top of this NDC without removing it.
     *
     * @return string The innermost diagnostic context.
     *
     * @access public
     */
    public static function peek(): string
    {
        $count = (static::$stack);

        if ($count > 0) {
            return end(static::$stack);
        }//end if

        return '';
    }

    /**
     * Push new diagnostic context information for the current thread.
     *
     * @param string $message The new diagnostic context information.
     *
     * @return void
     *
     * @access public
     */
    public static function push(string $message): void
    {
        array_push(static::$stack, $message);
    }

    /**
     * Remove the diagnostic context for this thread.
     *
     * @return void
     *
     * @access public
     */
    public static function remove(): void
    {
        static::clear();
    }

    /**
     * Set max depth of this diagnostic context. If current depth is smaller or equal to maxDepth, then no action.
     *
     * @param integer $maxDepth Max depth.
     *
     * @return void
     *
     * @access public
     */
    public static function setMaxDepth(int $maxDepth): void
    {
        if (static::getDepth() > $maxDepth) {
            static::$stack = array_slice(static::$stack, 0, $maxDepth);
        }//end if
    }
}
