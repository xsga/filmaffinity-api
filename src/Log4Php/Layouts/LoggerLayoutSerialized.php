<?php

namespace Log4Php\Layouts;

use Log4Php\LoggerLayout;
use Log4Php\LoggerLoggingEvent;

class LoggerLayoutSerialized extends LoggerLayout
{
    protected bool $locationInfo = false;

    public function setLocationInfo(bool $value): void
    {
        $this->setBoolean('locationInfo', $value);
    }

    public function getLocationInfo(): bool
    {
        return $this->locationInfo;
    }

    public function format(LoggerLoggingEvent $event): string
    {
        if ($this->locationInfo) {
            $event->getLocationInformation();
        }

        return serialize($event) . PHP_EOL;
    }
}
