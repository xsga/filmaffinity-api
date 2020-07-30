<?php
/**
 * LoggerAppenderRollingFile.
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
 * @subpackage Appenders
 */

namespace log4php\appenders;

use log4php\LoggerException;

/**
 * LoggerAppenderRollingFile writes logging events to a specified file. 
 * 
 * The file is rolled over after a specified size has been reached.
 * 
 * This appender uses a layout.
 *
 * ## Configurable parameters: ##
 * 
 * - **file** - Path to the target file.
 * - **append** - If set to true, the appender will append to the file, 
 *     otherwise the file contents will be overwritten.
 * - **maxBackupIndex** - Maximum number of backup files to keep. Default is 1.
 * - **maxFileSize** - Maximum allowed file size (in bytes) before rolling 
 *     over. Suffixes "KB", "MB" and "GB" are allowed. 10KB = 10240 bytes, etc.
 *     Default is 10M.
 * - **compress** - If set to true, rolled-over files will be compressed. 
 *     Requires the zlib extension.
 */
class LoggerAppenderRollingFile extends LoggerAppenderFile
{

    /**
     * Compressing backup files is done in chunks, this determines how large.
     * 
     * @var integer
     * 
     * @access public
     */
    const COMPRESS_CHUNK_SIZE = 102400;
    
    /**
     * The maximum size (in bytes) that the output file is allowed to reach before being rolled over to backup files.
     *
     * The default maximum file size is 10MB (10485760 bytes). Max value for this option may depend on the file system.
     *
     * @var integer
     * 
     * @access protected
     */
    protected $maxFileSize = 10485760;
    
    /**
     * Set the maximum number of backup files to keep around.
     * 
     * Determines how many backup files are kept before the oldest is erased. 
     * This option takes a positive integer value. If set to zero, then there will be no backup files and the log 
     * file will be truncated when it reaches maxFileSize.
     * 
     * There is one backup file by default.
     *
     * @var integer
     * 
     * @access protected
     */
    protected $maxBackupIndex = 1;
    
    /**
     * The compress parameter determindes the compression with zlib.
     *  
     * If set to true, the rollover files are compressed and saved with the .gz extension.
     * 
     * @var boolean
     * 
     * @access protected
     */
    protected $compress = false;

    /**
     * Set to true in the constructor if PHP >= 5.3.0.
     * 
     * In that case clearstatcache supports conditional clearing of statistics.
     * 
     * @var boolean
     * 
     * @access private
     */
    private $clearConditional = false;
    
    
    /**
     * Get the maximum size that the output file is allowed to reach before being rolled over to backup files.
     * 
     * @return integer
     * 
     * @access public
     */
    public function getMaximumFileSize()
    {
        return $this->maxFileSize;
        
    }//end getMaximumFileSize()
    

    /**
     * Constructor.
     * 
     * @param string $name Name.
     * 
     * @access public
     */
    public function __construct($name='')
    {
        parent::__construct($name);
        
        if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
            $this->clearConditional = true;
        }//end if
        
    }//end __construct()
    
    
    /**
     * Implements the usual roll over behaviour.
     *
     * @return void
     * 
     * @access private
     * 
     * @throws LoggerException If any part of the rollover procedure fails.
     */
    private function rollOver()
    {
        // If maxBackups <= 0, then there is no file renaming to be done.
        if ($this->maxBackupIndex > 0) {
            
            // Delete the oldest file, to keep Windows happy.
            $file = $this->file.'.'.$this->maxBackupIndex;
            
            if ((file_exists($file) === true) && (unlink($file) === false)) {
                throw new LoggerException('Unable to delete oldest backup file from ['.$file.'].');
            }//end if

            $this->renameArchievedLogs($this->file);
    
            // Backup the active file.
            $this->moveToBackup($this->file);
        }//end if
        
        // Truncate the active file.
        ftruncate($this->fp, 0);
        rewind($this->fp);
        
    }//end rollOver()
    
    
    /**
     * Move to backup.
     * 
     * @param string $source Source.
     * 
     * @return void
     * 
     * @access private
     */
    private function moveToBackup($source)
    {
        if ($this->compress === true) {
            $target = $source.'.1.gz';
            $this->compressFile($source, $target);
        } else {
            $target = $source.'.1';
            copy($source, $target);
        }//end if
        
    }//end moveToBackup()
    
    
    /**
     * Compress file.
     * 
     * @param string $source Source.
     * @param string $target Target.
     * 
     * @return void
     * 
     * @access private
     * 
     * @throws LoggerException Exception.
     */
    private function compressFile($source, $target)
    {
        $target = 'compress.zlib://'.$target;
        
        $fin = fopen($source, 'rb');
        if ($fin === false) {
            throw new LoggerException('Unable to open file for reading: ['.$source.'].');
        }//end if
        
        $fout = fopen($target, 'wb');
        if ($fout === false) {
            throw new LoggerException('Unable to open file for writing: ['.$target.'].');
        }//end if
    
        while (feof($fin) === false) {
            $chunk = fread($fin, static::COMPRESS_CHUNK_SIZE);
            if (false === fwrite($fout, $chunk)) {
                throw new LoggerException('Failed writing to compressed file.');
            }//end if
        }//end while
    
        fclose($fin);
        fclose($fout);
        
    }//end compressFile()
    
    
    /**
     * Rename archived logs.
     * 
     * @param string $fileName Filename.
     * 
     * @return void
     * 
     * @access private
     */
    private function renameArchievedLogs($fileName)
    {
        for ($i = ($this->maxBackupIndex - 1); $i >= 1; $i--) {
            
            $source = $fileName.'.'.$i;
            if ($this->compress === true) {
                $source .= '.gz';
            }//end if
            
            if (file_exists($source) === true) {
                $target = $fileName.'.'.($i + 1);
                if ($this->compress === true) {
                    $target .= '.gz';
                }//end if
                
                rename($source, $target);
            }//end if
        }//end for
        
    }//end renameArchievedLogs()
    
    
    /**
     * Writes a string to the target file. Opens file if not already open.
     * 
     * @param string $string Data to write.
     * 
     * @return void
     * 
     * @access protected
     */
    protected function write($string)
    {
        // Lazy file open.
        if ((isset($this->fp) === false) && ($this->openFile() === false)) {
            // Do not write if file open failed.
            return;
        }//end if
        
        // Lock the file while writing and possible rolling over.
        if (flock($this->fp, LOCK_EX) === true) {
            
            // Write to locked file.
            if (fwrite($this->fp, $string) === false) {
                $this->warn('Failed writing to file. Closing appender.');
                $this->closed = true;
            }//end if
            
            // Stats cache must be cleared, otherwise filesize() returns cached results.
            // If supported (PHP 5.3+), clear only the state cache for the target file.
            if ($this->clearConditional === true) {
                clearstatcache(true, $this->file);
            } else {
                clearstatcache();
            }//end if
            
            // Rollover if needed.
            if (filesize($this->file) > $this->maxFileSize) {
                try {
                    $this->rollOver();
                } catch (LoggerException $ex) {
                    $this->warn('Rollover failed: '.$ex->getMessage().'. Closing appender.');
                    $this->closed = true;
                }//end try
            }//end if
            
            flock($this->fp, LOCK_UN);
            
        } else {
            $this->warn('Failed locking file for writing. Closing appender.');
            $this->closed = true;
        }//end if
        
    }//end write()
    
    
    /**
     * Activate options.
     * 
     * @return void
     * 
     * @access public
     */
    public function activateOptions()
    {
        parent::activateOptions();
        
        if (($this->compress === true) && (extension_loaded('zlib') === false)) {
            $this->warn('The \'zlib\' extension is required for file compression. Disabling compression.');
            $this->compression = false;
        }//end if
        
    }//end activateOptions()
    
    
    /**
     * Set the 'maxBackupIndex' parameter.
     * 
     * @param integer $maxBackupIndex Max backup index.
     * 
     * @return void
     * 
     * @access public
     */
    public function setMaxBackupIndex($maxBackupIndex)
    {
        $this->setPositiveInteger('maxBackupIndex', $maxBackupIndex);
        
    }//end setMaxBackupIndex()
    
    
    /**
     * Returns the 'maxBackupIndex' parameter.
     * 
     * @return integer
     * 
     * @access public
     */
    public function getMaxBackupIndex()
    {
        return $this->maxBackupIndex;
        
    }//end getMaxBackupIndex()
    
    
    /**
     * Set the 'maxFileSize' parameter.
     * 
     * @param mixed $maxFileSizeMax File size.
     * 
     * @return void
     * 
     * @access public
     */
    public function setMaxFileSize($maxFileSize)
    {
        $this->setFileSize('maxFileSize', $maxFileSize);
        
    }//end setMaxFileSize()
    
    
    /**
     * Returns the 'maxFileSize' parameter.
     * 
     * @return integer
     * 
     * @access public
     */
    public function getMaxFileSize()
    {
        return $this->maxFileSize;
        
    }//end getMaxFileSize()
    
    
    /**
     * Set the 'maxFileSize' parameter (kept for backward compatibility).
     * 
     * @param mixed $maxFileSize Max file size.
     * 
     * @return void
     * 
     * @access public
     * 
     * @deprecated Use setMaxFileSize() instead.
     */
    public function setMaximumFileSize($maxFileSize)
    {
        $this->warn('The \'maximumFileSize\' parameter is deprecated. Use \'maxFileSize\' instead.');
        
        $this->setMaxFileSize($maxFileSize);
        
    }//end setMaximumFileSize()
    
    
    /**
     * Sets the 'compress' parameter.
     * 
     * @param boolean $compress Compress.
     * 
     * @return void
     * 
     * @access public
     */
    public function setCompress($compress)
    {
        $this->setBoolean('compress', $compress);
        
    }//end setCompress()
    
    
    /**
     * Returns the 'compress' parameter.
     * 
     * @return boolean
     * 
     * @access public
     */
    public function getCompress()
    {
        return $this->compress;
        
    }//end getCompress()
    
    
}//end LoggerAppenderRollingFile class
