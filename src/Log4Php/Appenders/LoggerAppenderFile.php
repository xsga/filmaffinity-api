<?php

/**
 * LoggerAppenderFile.
 *
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements. See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * PHP Version 8
 *
 * @package    Log4Php
 * @subpackage Appenders
 * @link       http://logging.apache.org/log4php/docs/appenders/file.html Appender documentation
 */

/**
 * Namespace.
 */
namespace Log4Php\Appenders;

/**
 * Import dependencies.
 */
use Log4Php\LoggerAppender;
use Log4Php\LoggerLoggingEvent;

/**
 * LoggerAppenderFile appends log events to a file.
 *
 * This appender uses a layout.
 *
 * ## Configurable parameters: ##
 *
 * - **file** - Path to the target file. Relative paths are resolved based on
 *     the working directory.
 * - **append** - If set to true, the appender will append to the file,
 *     otherwise the file contents will be overwritten.
 */
class LoggerAppenderFile extends LoggerAppender
{
    /**
     * If set to true, the file is locked before appending.
     *
     * Allows concurrent access. However, appending without locking is faster so it should be used where appropriate.
     *
     * @var boolean
     *
     * @access protected
     */
    protected $locking = true;

    /**
     * If set to true, appends to file. Otherwise overwrites it.
     *
     * @var boolean
     *
     * @access protected
     */
    protected $append = true;

    /**
     * Path to the target file.
     *
     * @var string
     *
     * @access protected
     */
    protected $file = '';

    /**
     * The file resource.
     *
     * @var resource|null
     *
     * @access protected
     */
    protected $fp = null;

    /**
     * Helper function which can be easily overriden by daily file appender.
     *
     * @return string
     *
     * @access protected
     */
    protected function getTargetFile(): string
    {
        return $this->file;
    }

    /**
     * Acquires the target file resource, creates the destination folder if necessary. Writes layout header to file.
     *
     * @return boolean
     *
     * @access protected
     */
    protected function openFile(): bool
    {
        $file = $this->getTargetFile();

        // Create the target folder if needed.
        if (!is_file($file)) {
            $dir = dirname($file);

            if (!is_dir($dir)) {
                $success = mkdir($dir, 0777, true);

                if (!$success) {
                    $this->warn('Failed creating target directory [' . $dir . ']. Closing appender.');
                    $this->closed = true;

                    return false;
                }//end if
            }//end if
        }//end if

        if ($this->append) {
            $mode = 'a';
        } else {
            $mode = 'w';
        }//end if

        $this->fp = fopen($file, $mode);

        if (!$this->fp) {
            $this->warn('Failed opening target file. Closing appender.');

            $this->fp     = null;
            $this->closed = true;

            return false;
        }//end if

        // Required when appending with concurrent access.
        if ($this->append) {
            fseek($this->fp, 0, SEEK_END);
        }//end if

        // Write the header.
        $this->write($this->layout->getHeader() ?? '');

        return true;
    }

    /**
     * Writes a string to the target file. Opens file if not already open.
     *
     * @param string|null $string Data to write.
     *
     * @return void
     *
     * @access protected
     */
    protected function write(string|null $string): void
    {
        // Lazy file open.
        if ($this->fp === null && !$this->openFile()) {
            // Do not write if file open failed.
            return;
        }//end if

        if ($this->locking) {
            $this->writeWithLocking($string);
            return;
        }//end if

        $this->writeWithoutLocking($string);
    }

    /**
     * Write with locking.
     *
     * @param string|null $string String.
     *
     * @return void
     *
     * @access protected
     */
    protected function writeWithLocking(string|null $string): void
    {
        if (flock($this->fp, LOCK_EX)) {
            if (fwrite($this->fp, is_null($string) ? '' : $string) === false) {
                $this->warn('Failed writing to file. Closing appender.');
                $this->closed = true;
            }//end if

            flock($this->fp, LOCK_UN);

            return;
        }//end if

        $this->warn('Failed locking file for writing. Closing appender.');
        $this->closed = true;
    }

    /**
     * Write without locking.
     *
     * @param string|null $string String.
     *
     * @return void
     *
     * @access protected
     */
    protected function writeWithoutLocking(string|null $string): void
    {
        if (fwrite($this->fp, $string) === false) {
            $this->warn('Failed writing to file. Closing appender.');
            $this->closed = true;
        }//end if
    }

    /**
     * Activate option.
     *
     * @return void
     *
     * @access public
     * @see    LoggerAppender::activateOptions()
     */
    public function activateOptions(): void
    {
        if (empty($this->file)) {
            $this->warn('Required parameter \'file\' not set. Closing appender.');
            $this->closed = true;
        }//end if
    }

    /**
     * Close.
     *
     * @return void
     *
     * @access public
     * @see    LoggerAppender::close()
     */
    public function close(): void
    {
        if ($this->fp !== null) {
            $this->write($this->layout->getFooter());
            fclose($this->fp);
        }//end if

        $this->fp     = null;
        $this->closed = true;
    }

    /**
     * Append.
     *
     * @param LoggerLoggingEvent $event Event.
     *
     * @return void
     *
     * @access public
     * @see    LoggerAppender::append()
     */
    public function append(LoggerLoggingEvent $event): void
    {
        $this->write($this->layout->format($event));
    }

    /**
     * Sets the 'file' parameter.
     *
     * @param string $file File.
     *
     * @return void
     *
     * @access public
     */
    public function setFile(string $file): void
    {
        $this->setString('file', $file);
    }

    /**
     * Returns the 'file' parameter.
     *
     * @return string
     *
     * @access public
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * Returns the 'append' parameter.
     *
     * @return boolean
     *
     * @access public
     */
    public function getAppend(): bool
    {
        return $this->append;
    }

    /**
     * Sets the 'append' parameter.
     *
     * @param boolean $append Append.
     *
     * @return void
     *
     * @access public
     */
    public function setAppend(bool $append): void
    {
        $this->setBoolean('append', $append);
    }

    /**
     * Returns the 'file' parmeter. Left for legacy reasons.
     *
     * @return string
     *
     * @access public
     *
     * @deprecated Use getFile() instead.
     */
    public function getFileName(): string
    {
        return $this->getFile();
    }
}
