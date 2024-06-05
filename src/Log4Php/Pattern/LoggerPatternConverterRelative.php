<?php

namespace Log4Php\Pattern;

use Log4Php\LoggerLoggingEvent;

class LoggerPatternConverterRelative extends LoggerPatternConverter
{
    public function convert(LoggerLoggingEvent $event): string
    {
        return number_format($event->getRelativeTime(), 4);
    }
}
