<?php

namespace Log4Php\Appenders;

use Log4Php\LoggerAppender;
use Log4Php\LoggerLoggingEvent;

class LoggerAppenderFile extends LoggerAppender
{
    protected bool $locking = true;
    protected bool $append = true;
    protected string $file = '';
    protected mixed $fp = null;

    protected function getTargetFile(): string
    {
        return $this->file;
    }

    protected function openFile(): bool
    {
        $file = $this->getTargetFile();

        if (!is_file($file)) {
            $dir = dirname($file);
            if (!is_dir($dir)) {
                $success = mkdir($dir, 0777, true);
                if (!$success) {
                    $this->warn("Failed creating target directory [$dir]. Closing appender.");
                    $this->closed = true;
                    return false;
                }
            }
        }

        $this->fp = fopen($file, $this->append ? 'a' : 'w');

        if (!$this->fp) {
            $this->warn('Failed opening target file. Closing appender.');
            $this->fp = null;
            $this->closed = true;
            return false;
        }

        if ($this->append) {
            fseek($this->fp, 0, SEEK_END);
        }

        $this->write($this->layout->getHeader() ?? '');

        return true;
    }

    protected function write(?string $string): void
    {
        if ($this->fp === null && !$this->openFile()) {
            return;
        }

        if ($this->locking) {
            $this->writeWithLocking($string);
            return;
        }

        $this->writeWithoutLocking($string);
    }

    protected function writeWithLocking(?string $string): void
    {
        if (flock($this->fp, LOCK_EX)) {
            if (fwrite($this->fp, $string === null ? '' : $string) === false) {
                $this->warn('Failed writing to file. Closing appender.');
                $this->closed = true;
            }
            flock($this->fp, LOCK_UN);
            return;
        }

        $this->warn('Failed locking file for writing. Closing appender.');
        $this->closed = true;
    }

    protected function writeWithoutLocking(?string $string): void
    {
        if (fwrite($this->fp, $string) === false) {
            $this->warn('Failed writing to file. Closing appender.');
            $this->closed = true;
        }
    }

    public function activateOptions(): void
    {
        if (empty($this->file)) {
            $this->warn("Required parameter 'file' not set. Closing appender.");
            $this->closed = true;
        }
    }

    public function close(): void
    {
        if ($this->fp !== null) {
            $this->write($this->layout->getFooter());
            fclose($this->fp);
        }

        $this->fp     = null;
        $this->closed = true;
    }

    public function append(LoggerLoggingEvent $event): void
    {
        $this->write($this->layout->format($event));
    }

    public function setFile(string $file): void
    {
        $this->setString('file', $file);
    }

    public function getFile(): string
    {
        return $this->file;
    }

    public function getAppend(): bool
    {
        return $this->append;
    }

    public function setAppend(bool $append): void
    {
        $this->setBoolean('append', $append);
    }
}
