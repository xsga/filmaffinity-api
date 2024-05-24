<?php

namespace Log4Php\Filters;

use Log4Php\LoggerFilter;
use Log4Php\LoggerLoggingEvent;

class LoggerFilterDenyAll extends LoggerFilter
{
    public function decide(LoggerLoggingEvent $event): int
    {
        return LoggerFilter::DENY;
    }
}
