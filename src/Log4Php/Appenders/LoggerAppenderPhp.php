<?php

namespace Log4Php\Appenders;

use Log4Php\LoggerAppender;
use Log4Php\LoggerLoggingEvent;
use Log4Php\LoggerLevel;

class LoggerAppenderPhp extends LoggerAppender
{
    public function append(LoggerLoggingEvent $event): void
    {
        $level = $event->getLevel();

        if ($level->isGreaterOrEqual(LoggerLevel::getLevelError())) {
            trigger_error($this->layout->format($event), E_USER_ERROR);
        }

        if ($level->isGreaterOrEqual(LoggerLevel::getLevelWarn())) {
            trigger_error($this->layout->format($event), E_USER_WARNING);
        }

        trigger_error($this->layout->format($event), E_USER_NOTICE);
    }
}
