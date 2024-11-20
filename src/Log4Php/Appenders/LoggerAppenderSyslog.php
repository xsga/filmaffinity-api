<?php

namespace Log4Php\Appenders;

use Log4Php\LoggerAppender;
use Log4Php\LoggerLoggingEvent;
use Log4Php\LoggerLevel;

class LoggerAppenderSyslog extends LoggerAppender
{
    protected string $ident = 'Apache log4php';
    protected string $priority = '';
    protected string $option = 'PID|CONS';
    protected string $facility = 'USER';
    protected bool $overridePriority = false;
    private int $intPriority = 0;
    private int $intFacility = 0;
    private int $intOption = 0;

    public function setIdent(string $ident): void
    {
        $this->ident = $ident;
    }

    public function setPriority(string $priority): void
    {
        $this->priority = $priority;
    }

    public function setFacility(string $facility): void
    {
        $this->facility = $facility;
    }

    public function setOverridePriority(bool $overridePriority): void
    {
        $this->overridePriority = $overridePriority;
    }

    public function setOption(string $option): void
    {
        $this->option = $option;
    }

    public function getIdent(): string
    {
        return $this->ident;
    }

    public function getPriority(): string
    {
        return $this->priority;
    }

    public function getFacility(): string
    {
        return $this->facility;
    }

    public function getOverridePriority(): bool
    {
        return $this->overridePriority;
    }

    public function getOption(): string
    {
        return $this->option;
    }

    public function activateOptions(): void
    {
        $this->intPriority = $this->parsePriority();
        $this->intOption   = $this->parseOption();
        $this->intFacility = (int)$this->parseFacility();
        $this->closed      = false;
    }

    public function close(): void
    {
        if (!$this->closed) {
            closelog();
            $this->closed = true;
        }
    }

    public function append(LoggerLoggingEvent $event): void
    {
        $priority = $this->getSyslogPriority($event->getLevel());
        $message  = $this->layout->format($event);

        openlog($this->ident, $this->intOption, $this->intFacility);
        syslog($priority, $message);
        closelog();
    }

    private function getSyslogPriority(LoggerLevel $level): int
    {
        if ($this->overridePriority) {
            return $this->intPriority;
        }

        return $level->getSyslogEquivalent();
    }

    private function parseOption(): int
    {
        $value = 0;
        $options = explode('|', $this->option);

        foreach ($options as $option) {
            if (!empty($option)) {
                $constant = 'LOG_' . trim($option);
                if (defined($constant)) {
                    $value |= constant($constant);
                } else {
                    $log = "log4php: Invalid syslog option provided: $option. Whole option string: $this->option.";
                    trigger_error($log, E_USER_WARNING);
                }
            }
        }

        return $value;
    }

    private function parseFacility(): string
    {
        if (!empty($this->facility)) {
            $constant = 'LOG_' . trim($this->facility);
            if (defined($constant)) {
                return constant($constant);
            }

            trigger_error("log4php: Invalid syslog facility provided: $this->facility.", E_USER_WARNING);
        }

        return '';
    }

    private function parsePriority(): mixed
    {
        if (!empty($this->priority)) {
            $constant = 'LOG_' . trim($this->priority);
            if (defined($constant)) {
                return constant($constant);
            }

            trigger_error("log4php: Invalid syslog priority provided: $this->priority.", E_USER_WARNING);
        }

        return null;
    }
}
