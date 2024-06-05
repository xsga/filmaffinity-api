<?php

namespace Log4Php\Pattern;

use Log4Php\LoggerLoggingEvent;

class LoggerPatternConverterNewLine extends LoggerPatternConverter
{
    public function convert(LoggerLoggingEvent $event): string
    {
        return PHP_EOL;
    }
}
