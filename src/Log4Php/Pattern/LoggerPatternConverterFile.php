<?php

namespace Log4Php\Pattern;

use Log4Php\LoggerLoggingEvent;

class LoggerPatternConverterFile extends LoggerPatternConverter
{
    public function convert(LoggerLoggingEvent $event): string
    {
        return $event->getLocationInformation()->getFileName();
    }
}
