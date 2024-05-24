<?php

namespace Log4Php\Pattern;

use Log4Php\LoggerLoggingEvent;

class LoggerPatternConverterSessionID extends LoggerPatternConverter
{
    public function convert(LoggerLoggingEvent $event): string
    {
        return session_id();
    }
}
