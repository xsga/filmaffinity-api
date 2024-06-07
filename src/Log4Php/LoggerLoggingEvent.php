<?php

namespace Log4Php;

use Exception;

class LoggerLoggingEvent
{
    private static float $startTime = 0.0;
    private ?Logger $logger;
    private string $categoryName;
    private ?string $ndc;
    private bool $ndcLookupRequired = true;
    private string $renderedMessage = '';
    private mixed $threadName;
    public float $timeStamp;
    private ?LoggerLocationInfo $locationInfo = null;
    private ?LoggerThrowableInformation $throwableInfo = null;

    public function __construct(
        private string $fqcn,
        Logger|string $logger,
        protected LoggerLevel $level,
        private mixed $message,
        ?int $timeStamp = null,
        Exception $throwable = null
    ) {
        $this->timeStamp = match (is_null($timeStamp)) {
            true => microtime(true),
            false => $timeStamp
        };

        if ($throwable !== null) {
            $this->throwableInfo = new LoggerThrowableInformation($throwable);
        }

        if (($logger instanceof Logger)) {
            $this->logger       = $logger;
            $this->categoryName = $logger->getName();
            return;
        }

        $this->categoryName = strval($logger);
    }

    public function getFullQualifiedClassname(): string
    {
        return $this->fqcn;
    }

    public function getLocationInformation(): LoggerLocationInfo
    {
        if (is_null($this->locationInfo)) {
            $locationInfo = [];
            $trace        = debug_backtrace();
            $prevHop      = null;

            $hop = array_pop($trace);

            while ($hop !== null) {
                if (isset($hop['class'])) {
                    $className = strtolower(str_replace('log4php\\', '', strtolower($hop['class'])));

                    if (
                        !empty($className) && (
                        $className === 'logger' ||
                        $className === 'loggerwrapper' ||
                        strtolower(get_parent_class($className)) === 'logger')
                    ) {
                        $locationInfo['line'] = $hop['line'];
                        $locationInfo['file'] = $hop['file'];
                        break;
                    }
                }

                $prevHop = $hop;
                $hop     = array_pop($trace);
            }

            $locationInfo['class'] = $prevHop['class'] ?? 'main';

            match (
                isset($prevHop['function']) &&
                $prevHop['function'] !== 'include' &&
                $prevHop['function'] !== 'include_once' &&
                $prevHop['function'] !== 'require' &&
                $prevHop['function'] !== 'require_once'
            ) {
                true => $locationInfo['function'] = $prevHop['function'],
                false => $locationInfo['function'] = 'main'
            };

            $this->locationInfo = new LoggerLocationInfo($locationInfo);
        }

        return $this->locationInfo;
    }

    public function getLevel(): LoggerLevel
    {
        return $this->level;
    }

    public function getLogger(): Logger
    {
        return $this->logger;
    }

    public function getLoggerName(): string
    {
        return $this->categoryName;
    }

    public function getMessage(): mixed
    {
        return $this->message;
    }

    public function getNDC(): ?string
    {
        if ($this->ndcLookupRequired) {
            $this->ndcLookupRequired = false;
            $this->ndc               = LoggerNDC::get();
        }

        return $this->ndc;
    }

    public function getMDC($key): string
    {
        return LoggerMDC::get($key);
    }

    public function getMDCMap(): array
    {
        return LoggerMDC::getMap();
    }

    public function getRenderedMessage(): string
    {
        if (empty($this->renderedMessage) && !is_null($this->message)) {
            $this->setRenderedMessage();
        }

        return $this->renderedMessage;
    }

    private function setRenderedMessage(): void
    {
        if (is_string($this->message)) {
            $this->renderedMessage = $this->message;
            return;
        }

        $rendererMap = Logger::getHierarchy()->getRendererMap();
        $this->renderedMessage = $rendererMap->findAndRender($this->message);
    }

    public static function getStartTime(): float
    {
        if (static::$startTime === 0.0) {
            static::$startTime = microtime(true);
        }

        return static::$startTime;
    }

    public function getTimeStamp(): float
    {
        return $this->timeStamp;
    }

    public function getRelativeTime(): float
    {
        return ($this->timeStamp - static::$startTime);
    }

    public function getThreadName(): mixed
    {
        if (is_null($this->threadName)) {
            $this->threadName = (string)getmypid();
        }

        return $this->threadName;
    }

    public function getThrowableInformation(): mixed
    {
        return $this->throwableInfo;
    }

    public function toString(): void
    {
        serialize($this);
    }

    public function __sleep(): array
    {
        return [
            'fqcn',
            'categoryName',
            'level',
            'ndc',
            'ndcLookupRequired',
            'message',
            'renderedMessage',
            'threadName',
            'timeStamp',
            'locationInfo'
        ];
    }
}
