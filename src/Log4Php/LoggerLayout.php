<?php

namespace Log4Php;

abstract class LoggerLayout extends LoggerConfigurable
{
    public function activateOptions(): void
    {
    }

    public function format(LoggerLoggingEvent $event): string
    {
        return $event->getRenderedMessage();
    }

    public function getContentType(): string
    {
        return 'text/plain';
    }

    public function getFooter(): ?string
    {
        return null;
    }

    public function getHeader(): ?string
    {
        return null;
    }

    protected function warn(string $message): void
    {
        trigger_error('log4php: [' . get_class($this) . ']: ' . $message, E_USER_WARNING);
    }
}
