<?php

namespace Log4Php;

use Log4Php\Layouts\LoggerLayoutSimple;

abstract class LoggerAppender extends LoggerConfigurable
{
    protected bool $closed = false;
    protected ?LoggerFilter $filter = null;
    protected ?LoggerLayout $layout = null;
    protected ?LoggerLevel $threshold = null;
    protected bool $requiresLayout = true;

    public function __construct(private string $name = '')
    {
        if ($this->requiresLayout) {
            $this->layout = $this->getDefaultLayout();
        }
    }

    public function __destruct()
    {
        $this->close();
    }

    public function getDefaultLayout(): LoggerLayout
    {
        return new LoggerLayoutSimple();
    }

    public function addFilter(LoggerFilter $filter): void
    {
        if ($this->filter === null) {
            $this->filter = $filter;
            return;
        }

        $this->filter->addNext($filter);
    }

    public function clearFilters(): void
    {
        $this->filter = null;
    }

    public function getFilter(): ?LoggerFilter
    {
        return $this->filter;
    }

    public function getFirstFilter(): ?LoggerFilter
    {
        return $this->filter;
    }

    public function doAppend(LoggerLoggingEvent $event): void
    {
        if ($this->closed) {
            return;
        }

        if (!$this->isAsSevereAsThreshold($event->getLevel())) {
            return;
        }

        $filter = $this->getFirstFilter();

        while ($filter !== null) {
            switch ($filter->decide($event)) {
                case LoggerFilter::DENY:
                    return;
                case LoggerFilter::ACCEPT:
                    $this->append($event);
                    return;
                case LoggerFilter::NEUTRAL:
                    $filter = $filter->getNext();
                    return;
                default:
                    break;
            }
        }

        $this->append($event);
    }

    public function setLayout(LoggerLayout $layout): void
    {
        if ($this->requiresLayout()) {
            $this->layout = $layout;
        }
    }

    public function getLayout(): ?LoggerLayout
    {
        return $this->layout;
    }

    public function requiresLayout(): bool
    {
        return $this->requiresLayout;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getThreshold(): ?LoggerLevel
    {
        return $this->threshold;
    }

    public function setThreshold(LoggerLevel $threshold): void
    {
        $this->setLevel('threshold', $threshold);
    }

    public function isAsSevereAsThreshold(LoggerLevel $level): bool
    {
        if ($this->threshold === null) {
            return true;
        }

        return $level->isGreaterOrEqual($this->getThreshold());
    }

    public function activateOptions(): void
    {
        $this->closed = false;
    }

    abstract protected function append(LoggerLoggingEvent $event): void;

    public function close(): void
    {
        $this->closed = true;
    }

    protected function warn(mixed $message): void
    {
        $id = get_class($this) . (empty($this->name) ? '' : ": $this->name");
        trigger_error("log4php: [$id]: $message", E_USER_WARNING);
    }
}
