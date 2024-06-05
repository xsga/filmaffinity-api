<?php

namespace Log4Php\Pattern;

use Log4Php\LoggerLoggingEvent;

class LoggerPatternConverterMDC extends LoggerPatternConverter
{
    private ?string $key;

    public function activateOptions(): void
    {
        if (isset($this->option) && ($this->option !== '')) {
            $this->key = $this->option;
        }
    }

    public function convert(LoggerLoggingEvent $event): string
    {
        if (isset($this->key)) {
            $out = $event->getMDC($this->key);
        } else {
            $buff = [];
            $map  = $event->getMDCMap();

            foreach ($map as $key => $value) {
                $buff[] = $key . '=' . $value;
            }

            $out = implode(', ', $buff);
        }

        return $out;
    }
}
