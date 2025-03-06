<?php

namespace Log4Php\Appenders;

use Log4Php\LoggerLoggingEvent;

class LoggerAppenderDailyFile extends LoggerAppenderFile
{
    protected string $datePattern = "Ymd";
    protected string $currentDate = '';

    public function activateOptions(): void
    {
        parent::activateOptions();

        if (empty($this->datePattern)) {
            $this->warn("Required parameter 'datePattern' not set. Closing appender.");
            $this->closed = true;
        }
    }

    public function append(LoggerLoggingEvent $event): void
    {
        $eventDate = $this->getDate($event->getTimestamp());

        if ($this->currentDate === '') {
            $this->currentDate = $eventDate;
        } elseif ($this->currentDate !== $eventDate) {
            $this->currentDate = $eventDate;

            if ($this->fp !== null) {
                $this->write($this->layout->getFooter());
                fclose($this->fp);
            }

            $this->fp = null;
        }

        parent::append($event);
    }

    protected function getDate(?float $timestamp = null): string
    {
        return date($this->datePattern, (int)$timestamp);
    }

    protected function getTargetFile(): string
    {
        return str_replace('%s', $this->currentDate, $this->file);
    }

    public function setDatePattern(string $datePattern): void
    {
        $this->setString('datePattern', $datePattern);
    }

    public function getDatePattern(): string
    {
        return $this->datePattern;
    }
}
