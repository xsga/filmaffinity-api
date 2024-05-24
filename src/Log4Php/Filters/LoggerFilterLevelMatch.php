<?php

namespace Log4Php\Filters;

use Log4Php\LoggerFilter;
use Log4Php\LoggerLoggingEvent;
use Log4Php\LoggerLevel;

class LoggerFilterLevelMatch extends LoggerFilter
{
    protected bool $acceptOnMatch = true;
    protected ?LoggerLevel $levelToMatch = null;

    public function setAcceptOnMatch(bool $acceptOnMatch): void
    {
        $this->setBoolean('acceptOnMatch', $acceptOnMatch);
    }

    public function setLevelToMatch(string $level): void
    {
        $this->setLevel('levelToMatch', $level);
    }

    public function decide(LoggerLoggingEvent $event): int
    {
        if ($this->levelToMatch === null) {
            return LoggerFilter::NEUTRAL;
        }

        if ($this->levelToMatch->equals($event->getLevel())) {
            if ($this->acceptOnMatch) {
                return LoggerFilter::ACCEPT;
            }
            return LoggerFilter::DENY;
        }
        return LoggerFilter::NEUTRAL;
    }
}
