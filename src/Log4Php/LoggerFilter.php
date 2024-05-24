<?php

namespace Log4Php;

abstract class LoggerFilter extends LoggerConfigurable
{
    public const int ACCEPT = 1;
    public const int NEUTRAL = 0;
    public const int DENY = -1;

    protected ?LoggerFilter $next = null;

    public function activateOptions(): void
    {
    }

    public function decide(LoggerLoggingEvent $event): int
    {
        return static::NEUTRAL;
    }

    public function addNext(LoggerFilter $filter): void
    {
        if ($this->next !== null) {
            $this->next->addNext($filter);
            return;
        }

        $this->next = $filter;
    }

    public function getNext(): mixed
    {
        return $this->next;
    }
}
