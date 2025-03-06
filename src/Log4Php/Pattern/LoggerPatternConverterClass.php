<?php

namespace Log4Php\Pattern;

use Log4Php\LoggerLoggingEvent;
use Log4Php\Helpers\LoggerUtils;

class LoggerPatternConverterClass extends LoggerPatternConverter
{
    private int $length = -1;
    private array $cache = [];

    public function activateOptions(): void
    {
        if ($this->option !== null && is_numeric($this->option) && $this->option >= 0) {
            $this->length = (int)$this->option;
        }
    }

    public function convert(LoggerLoggingEvent $event): string
    {
        $name = $event->getLocationInformation()->getClassName();

        if (!isset($this->cache[$name])) {
            $this->cache[$name] = LoggerUtils::shortenClassName($name, $this->length);
        }

        return $this->cache[$name];
    }
}
