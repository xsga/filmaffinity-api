<?php

namespace Log4Php\Filters;

use Log4Php\LoggerFilter;
use Log4Php\LoggerLoggingEvent;

class LoggerFilterStringMatch extends LoggerFilter
{
    protected bool $acceptOnMatch = true;
    protected string $stringToMatch = '';

    public function setAcceptOnMatch(mixed $acceptOnMatch): void
    {
        $this->setBoolean('acceptOnMatch', $acceptOnMatch);
    }

    public function setStringToMatch(string $string): void
    {
        $this->setString('stringToMatch', $string);
    }

    public function decide(LoggerLoggingEvent $event): int
    {
        $msg = $event->getRenderedMessage();

        if (empty($msg) || empty($this->stringToMatch)) {
            return LoggerFilter::NEUTRAL;
        }

        if (strpos($msg, $this->stringToMatch) !== false) {
            if ($this->acceptOnMatch) {
                return LoggerFilter::ACCEPT;
            }
            return LoggerFilter::DENY;
        }

        return LoggerFilter::NEUTRAL;
    }
}
