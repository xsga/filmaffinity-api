<?php

namespace Log4Php\Pattern;

use Log4Php\Helpers\LoggerFormattingInfo;
use Log4Php\LoggerLoggingEvent;

abstract class LoggerPatternConverter
{
    public ?LoggerPatternConverter $next = null;
    protected ?LoggerFormattingInfo $formattingInfo;
    protected ?string $option;

    public function __construct(?LoggerFormattingInfo $formattingInfo = null, ?string $option = null)
    {
        $this->formattingInfo = $formattingInfo;
        $this->option         = $option;

        $this->activateOptions();
    }

    public function activateOptions(): void
    {
    }

    abstract public function convert(LoggerLoggingEvent $event): mixed;

    public function format(string &$sbuf, LoggerLoggingEvent $event): void
    {
        $string = $this->convert($event);

        if (!isset($this->formattingInfo)) {
            $sbuf .= $string;
            return;
        }

        $fi = $this->formattingInfo;

        if ($string === '' || $string === null) {
            if ($fi->min > 0) {
                $sbuf .= str_repeat(' ', $fi->min);
            }
            return;
        }

        $len = strlen($string);

        if ($len > $fi->max) {
            if ($fi->trimLeft) {
                $sbuf .= substr($string, ($len - $fi->max), $fi->max);
            } else {
                $sbuf .= substr($string, 0, $fi->max);
            }
        } elseif ($len < $fi->min) {
            if ($fi->padLeft) {
                $sbuf .= str_repeat(' ', ($fi->min - $len));
                $sbuf .= $string;
            } else {
                $sbuf .= $string;
                $sbuf .= str_repeat(' ', ($fi->min - $len));
            }
        } else {
            $sbuf .= $string;
        }
    }
}
