<?php

namespace Log4Php\Appenders;

use Log4Php\LoggerAppender;
use Log4Php\LoggerLoggingEvent;

class LoggerAppenderConsole extends LoggerAppender
{
    public const string STDOUT = 'php://stdout';
    public const string STDERR = 'php://stderr';

    protected string $target = self::STDOUT;
    protected mixed $fp = null;

    public function activateOptions(): void
    {
        $this->fp = fopen($this->target, 'w');

        if (is_resource($this->fp) && ($this->layout !== null)) {
            fwrite($this->fp, $this->layout->getHeader() ?? '');
        }

        if (!is_resource($this->fp)) {
            $this->closed = true;
            return;
        }

        $this->closed = false;
    }

    public function close(): void
    {
        if ($this->closed !== true) {
            if (!is_null($this->fp) && ($this->layout !== null)) {
                fwrite($this->fp, $this->layout->getFooter() ?? '');
                fclose($this->fp);
            }

            $this->closed = true;
        }
    }

    public function append(LoggerLoggingEvent $event): void
    {
        if (is_resource($this->fp) && ($this->layout !== null)) {
            fwrite($this->fp, $this->layout->format($event));
        }
    }

    public function setTarget(string $target): void
    {
        $value = trim($target);

        if (($value === static::STDOUT) || (strtoupper($value) === 'STDOUT')) {
            $this->target = static::STDOUT;
            return;
        }

        if (($value === static::STDERR) || (strtoupper($value) === 'STDERR')) {
            $this->target = static::STDERR;
            return;
        }

        $this->warn("Invalid value given for 'target' property: [$value]. Property not set.");
    }

    public function getTarget(): string
    {
        return $this->target;
    }
}
