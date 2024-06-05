<?php

namespace Log4Php\Appenders;

use Log4Php\LoggerAppender;
use Log4Php\LoggerLoggingEvent;

class LoggerAppenderEcho extends LoggerAppender
{
    protected bool $firstAppend = true;
    protected bool $htmlLineBreaks = false;

    public function close(): void
    {
        if (!$this->closed && !$this->firstAppend) {
            echo $this->layout->getFooter();
        }

        $this->closed = true;
    }

    public function append(LoggerLoggingEvent $event): void
    {
        if ($this->layout !== null) {
            if ($this->firstAppend) {
                echo $this->layout->getHeader();
                $this->firstAppend = false;
            }

            $text = $this->layout->format($event);

            if ($this->htmlLineBreaks) {
                $text = nl2br($text);
            }

            echo $text;
        }
    }

    public function setHtmlLineBreaks(bool $value): void
    {
        $this->setBoolean('htmlLineBreaks', $value);
    }

    public function getHtmlLineBreaks(): bool
    {
        return $this->htmlLineBreaks;
    }
}
