<?php

namespace Log4Php\Pattern;

use Log4Php\LoggerLoggingEvent;

class LoggerPatternConverterLocation extends LoggerPatternConverter
{
    public function convert(LoggerLoggingEvent $event): string
    {
        $out  = $event->getLocationInformation()->getClassName() . '.';
        $out .= $event->getLocationInformation()->getMethodName() . '(';
        $out .= $event->getLocationInformation()->getFileName() . ':';
        $out .= $event->getLocationInformation()->getLineNumber() . ')';

        return $out;
    }
}
