<?php

namespace Log4Php\Appenders;

use Log4Php\LoggerAppender;
use Log4Php\LoggerLoggingEvent;

class LoggerAppenderNull extends LoggerAppender
{
    protected bool $requiresLayout = false;

    public function append(LoggerLoggingEvent $event): void
    {
    }
}
