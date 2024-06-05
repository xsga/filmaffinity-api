<?php

namespace Log4Php\Pattern;

use Log4Php\LoggerLoggingEvent;

class LoggerPatternConverterProcess extends LoggerPatternConverter
{
    public function convert(LoggerLoggingEvent $event): bool|int
    {
        return getmypid();
    }
}
