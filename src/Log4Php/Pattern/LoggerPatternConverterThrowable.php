<?php

namespace Log4Php\Pattern;

use Log4Php\LoggerLoggingEvent;

class LoggerPatternConverterThrowable extends LoggerPatternConverter
{
    public function convert(LoggerLoggingEvent $event): string
    {
        $info = $event->getThrowableInformation();
        if (isset($info)) {
            $ex = $info->getThrowable();
            return (string)$ex . PHP_EOL;
        }

        return '';
    }
}
