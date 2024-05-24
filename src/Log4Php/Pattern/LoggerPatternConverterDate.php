<?php

namespace Log4Php\Pattern;

use Log4Php\LoggerLoggingEvent;

class LoggerPatternConverterDate extends LoggerPatternConverter
{
    public const string DATE_FORMAT_ISO8601 = 'c';
    public const string DATE_FORMAT_ABSOLUTE = 'H:i:s';
    public const string DATE_FORMAT_DATE = 'd M Y H:i:s.u';

    private string $format = self::DATE_FORMAT_ISO8601;
    private array $specials = [
        'ISO8601'  => self::DATE_FORMAT_ISO8601,
        'ABSOLUTE' => self::DATE_FORMAT_ABSOLUTE,
        'DATE'     => self::DATE_FORMAT_DATE,
    ];
    private bool $useLocalDate = false;

    public function activateOptions(): void
    {
        if (!empty($this->option)) {
            if (isset($this->specials[$this->option])) {
                $this->format = $this->specials[$this->option];
            } else {
                $this->format = $this->option;
            }
        }

        if (preg_match('/(?<!\\\\)u/', $this->format) === 0) {
            $this->useLocalDate = true;
        }
    }

    public function convert(LoggerLoggingEvent $event): string
    {
        if ($this->useLocalDate) {
            return $this->date($this->format, $event->getTimeStamp());
        }

        return date($this->format, (int)$event->getTimeStamp());
    }

    private function date(string $format, float $utimestamp): string
    {
        $timestamp = floor($utimestamp);
        $ms        = floor(($utimestamp - $timestamp) * 1000);
        $msString  = str_pad((string)$ms, 3, '0', STR_PAD_LEFT);

        return date(preg_replace('`(?<!\\\\)u`', $msString, $format), (int)$timestamp);
    }
}
