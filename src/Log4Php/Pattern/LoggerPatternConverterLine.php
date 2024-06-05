<?php

namespace Log4Php\Pattern;

use Log4Php\LoggerLoggingEvent;

class LoggerPatternConverterLine extends LoggerPatternConverter
{
    public function convert(LoggerLoggingEvent $event): string|int
    {
        return $event->getLocationInformation()->getLineNumber();
    }
}
