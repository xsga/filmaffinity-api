<?php

namespace Log4Php\Pattern;

use Log4Php\LoggerLoggingEvent;

class LoggerPatternConverterMethod extends LoggerPatternConverter
{
    public function convert(LoggerLoggingEvent $event): string
    {
        return $event->getLocationInformation()->getMethodName();
    }
}
