<?php

namespace Log4Php\Filters;

use Log4Php\LoggerFilter;
use Log4Php\LoggerLoggingEvent;
use Log4Php\LoggerLevel;

class LoggerFilterLevelRange extends LoggerFilter
{
    protected bool $acceptOnMatch = true;
    protected ?LoggerLevel $levelMin = null;
    protected ?LoggerLevel $levelMax = null;

    public function setAcceptOnMatch(bool $acceptOnMatch): void
    {
        $this->setBoolean('acceptOnMatch', $acceptOnMatch);
    }

    public function setLevelMin(string $level): void
    {
        $this->setLevel('levelMin', $level);
    }

    public function setLevelMax(string $level): void
    {
        $this->setLevel('levelMax', $level);
    }

    public function decide(LoggerLoggingEvent $event): int
    {
        $level = $event->getLevel();

        if (($this->levelMin !== null) && !$level->isGreaterOrEqual($this->levelMin)) {
            return LoggerFilter::DENY;
        }

        if (($this->levelMax !== null) && ($level->toInt() > $this->levelMax->toInt())) {
            return LoggerFilter::DENY;
        }

        if ($this->acceptOnMatch) {
            return LoggerFilter::ACCEPT;
        }

        return LoggerFilter::NEUTRAL;
    }
}
