<?php

namespace Log4Php\Pattern;

use Log4Php\LoggerLoggingEvent;

class LoggerPatternConverterLiteral extends LoggerPatternConverter
{
    private string $literalValue;

    public function __construct(string $literalValue)
    {
        $this->literalValue = $literalValue;
    }

    public function convert(LoggerLoggingEvent $event): string
    {
        return $this->literalValue;
    }
}
