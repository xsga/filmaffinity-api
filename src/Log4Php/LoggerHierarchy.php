<?php

namespace Log4Php;

use Log4Php\Renderers\LoggerRendererMap;

class LoggerHierarchy
{
    protected array $loggers = [];
    protected LoggerRendererMap $rendererMap;
    protected LoggerLevel $threshold;

    public function __construct(private LoggerRoot $root)
    {
        $this->setThreshold(LoggerLevel::getLevelAll());
        $this->rendererMap = new LoggerRendererMap();
    }

    public function clear(): void
    {
        $this->loggers = [];
    }

    public function exists(string $name): bool
    {
        return isset($this->loggers[$name]);
    }

    public function getCurrentLoggers(): array
    {
        return array_values($this->loggers);
    }

    public function getLogger(string $name): Logger
    {
        if (!isset($this->loggers[$name])) {
            $logger    = new Logger($name);
            $nodes     = explode('.', $name);
            $firstNode = array_shift($nodes);

            match ($firstNode !== $name && isset($this->loggers[$firstNode])) {
                true => $logger->setParent($this->loggers[$firstNode]),
                false => $logger->setParent($this->root)
            };

            if (count($nodes) > 0) {
                foreach ($nodes as $node) {
                    $parentNode = $firstNode . $node;

                    if (isset($this->loggers[$parentNode]) && ($parentNode !== $name)) {
                        $logger->setParent($this->loggers[$parentNode]);
                    }

                    $firstNode .= $node;
                }
            }

            $this->loggers[$name] = $logger;
        }

        return $this->loggers[$name];
    }

    public function getRendererMap(): LoggerRendererMap
    {
        return $this->rendererMap;
    }

    public function getRootLogger(): LoggerRoot
    {
        return $this->root;
    }

    public function getThreshold(): LoggerLevel
    {
        return $this->threshold;
    }

    public function isDisabled(LoggerLevel $level): bool
    {
        if ($this->threshold->toInt() > $level->toInt()) {
            return true;
        }

        return false;
    }

    public function resetConfiguration(): void
    {
        $root = $this->getRootLogger();

        $root->setLevel(LoggerLevel::getLevelDebug());
        $this->setThreshold(LoggerLevel::getLevelAll());
        $this->shutDown();

        foreach ($this->loggers as $logger) {
            $logger->setLevel(null);
            $logger->setAdditivity(true);
            $logger->removeAllAppenders();
        }

        $this->rendererMap->reset();
        LoggerAppenderPool::clear();
    }

    public function setThreshold(LoggerLevel $threshold): void
    {
        $this->threshold = $threshold;
    }

    public function shutdown(): void
    {
        $this->root->removeAllAppenders();

        foreach ($this->loggers as $logger) {
            $logger->removeAllAppenders();
        }
    }

    public function printHierarchy(): void
    {
        $this->printHierarchyInner($this->getRootLogger(), 0);
    }

    private function printHierarchyInner(Logger $current, int $level): void
    {
        for ($i = 0; $i < $level; $i++) {
            echo ($i === $level - 1) ? '|--' : '|  ';
        }

        echo $current->getName() . "\n";

        foreach ($this->loggers as $logger) {
            if ($logger->getParent() === $current) {
                $this->printHierarchyInner($logger, ($level + 1));
            }
        }
    }
}
