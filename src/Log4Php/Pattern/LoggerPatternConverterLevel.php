<?php

namespace Log4Php\Pattern;

use Log4Php\LoggerLoggingEvent;

class LoggerPatternConverterLevel extends LoggerPatternConverter
{
    public function convert(LoggerLoggingEvent $event): string
    {
        return $event->getLevel()->toString();
    }
}
