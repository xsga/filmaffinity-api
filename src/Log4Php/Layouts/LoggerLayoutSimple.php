<?php

namespace Log4Php\Layouts;

use Log4Php\LoggerLayout;
use Log4Php\LoggerLoggingEvent;

class LoggerLayoutSimple extends LoggerLayout
{
    public function format(LoggerLoggingEvent $event): string
    {
        $level   = $event->getLevel();
        $message = $event->getRenderedMessage();

        return $level . ' - ' . $message . PHP_EOL;
    }
}
