<?php

namespace Log4Php\Pattern;

use Log4Php\LoggerLoggingEvent;

abstract class LoggerPatternConverterSuperglobal extends LoggerPatternConverter
{
    protected string $name;
    protected string $value = '';

    public function activateOptions(): void
    {
        if (isset($this->option) && ($this->option !== '')) {
            $key = $this->option;
        }

        $GLOBALS[$this->name];

        if (!isset($GLOBALS[$this->name])) {
            $class = get_class($this);
            trigger_error('log4php: ' . $class . ': Cannot find superglobal variable $' . $this->name, E_USER_WARNING);
            return;
        }

        $source = $GLOBALS[$this->name];

        if (isset($key)) {
            if (isset($source[$key])) {
                $this->value = $source[$key];
                if (empty($this->value)) {
                    $this->value = 'empty';
                }
            } else {
                $this->value = 'empty';
            }
        } else {
            $values = [];
            foreach ($source as $key => $value) {
                $values[] = $key . '=' . $value;
            }
            $this->value = implode(', ', $values);
        }
    }

    public function convert(LoggerLoggingEvent $event): string
    {
        return $this->value;
    }
}
