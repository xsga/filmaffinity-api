<?php

namespace Log4Php;

use Exception;
use Log4Php\Configurators\LoggerConfiguratorDefault;

class Logger
{
    private bool $additive = true;
    private string $fqcn = 'Logger';
    private ?LoggerLevel $level = null;
    private ?Logger $parent = null;
    private array $appenders = [];
    private static ?LoggerHierarchy $hierarchy = null;
    private static bool $initialized = false;

    public function __construct(private string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getParent(): ?Logger
    {
        return $this->parent;
    }

    public function trace(mixed $message, Exception $throwable = null): void
    {
        $this->log(LoggerLevel::getLevelTrace(), $message, $throwable);
    }

    public function debug(mixed $message, Exception $throwable = null): void
    {
        $this->log(LoggerLevel::getLevelDebug(), $message, $throwable);
    }

    public function info(mixed $message, Exception $throwable = null): void
    {
        $this->log(LoggerLevel::getLevelInfo(), $message, $throwable);
    }

    public function warn(mixed $message, Exception $throwable = null): void
    {
        $this->log(LoggerLevel::getLevelWarn(), $message, $throwable);
    }

    public function error(mixed $message, Exception $throwable = null): void
    {
        $this->log(LoggerLevel::getLevelError(), $message, $throwable);
    }

    public function fatal(mixed $message, Exception $throwable = null): void
    {
        $this->log(LoggerLevel::getLevelFatal(), $message, $throwable);
    }

    public function log(LoggerLevel $level, mixed $message, Exception $throwable = null): void
    {
        if ($this->isEnabledFor($level)) {
            $event = new LoggerLoggingEvent($this->fqcn, $this, $level, $message, null, $throwable);
            $this->callAppenders($event);
        }

        if (!is_null($this->parent) && $this->getAdditivity()) {
            if (isset($event)) {
                $this->parent->logEvent($event);
                return;
            }
            $this->parent->log($level, $message, $throwable);
        }
    }

    public function logEvent(LoggerLoggingEvent $event): void
    {
        if ($this->isEnabledFor($event->getLevel())) {
            $this->callAppenders($event);
        }

        if (!is_null($this->parent) && $this->getAdditivity()) {
            $this->parent->logEvent($event);
        }
    }

    public function assertLog(bool $assertion = true, string $msg = ''): void
    {
        if (!$assertion) {
            $this->error($msg);
        }
    }

    public function forcedLog(string $fqcn, Exception $throwable, LoggerLevel $level, mixed $message): void
    {
        $event = new LoggerLoggingEvent($fqcn, $this, $level, $message, null, $throwable);
        $this->callAppenders($event);

        if (!is_null($this->parent) && $this->getAdditivity()) {
            $this->parent->logEvent($event);
        }
    }

    public function callAppenders(LoggerLoggingEvent $event): void
    {
        foreach ($this->appenders as $appender) {
            $appender->doAppend($event);
        }
    }

    public function isEnabledFor(LoggerLevel $level): bool
    {
        return $level->isGreaterOrEqual($this->getEffectiveLevel());
    }

    public function isTraceEnabled(): bool
    {
        return $this->isEnabledFor(LoggerLevel::getLevelTrace());
    }

    public function isDebugEnabled(): bool
    {
        return $this->isEnabledFor(LoggerLevel::getLevelDebug());
    }

    public function isInfoEnabled(): bool
    {
        return $this->isEnabledFor(LoggerLevel::getLevelInfo());
    }

    public function isWarnEnabled(): bool
    {
        return $this->isEnabledFor(LoggerLevel::getLevelWarn());
    }

    public function isErrorEnabled(): bool
    {
        return $this->isEnabledFor(LoggerLevel::getLevelError());
    }

    public function isFatalEnabled(): bool
    {
        return $this->isEnabledFor(LoggerLevel::getLevelFatal());
    }

    public function addAppender(LoggerAppender $appender): void
    {
        $appenderName = $appender->getName();
        $this->appenders[$appenderName] = $appender;
    }

    public function removeAllAppenders(): void
    {
        foreach (array_keys($this->appenders) as $name) {
            $this->removeAppender($name);
        }
    }

    public function removeAppender(string|LoggerAppender $appender): void
    {
        if ($appender instanceof LoggerAppender) {
            $appender->close();
            unset($this->appenders[$appender->getName()]);
            return;
        }

        if (isset($this->appenders[$appender])) {
            $this->appenders[$appender]->close();
            unset($this->appenders[$appender]);
        }
    }

    public function getAllAppenders(): array
    {
        return $this->appenders;
    }

    public function getAppender(string $name): LoggerAppender
    {
        return $this->appenders[$name];
    }

    public function setAdditivity(bool $additive): void
    {
        $this->additive = $additive;
    }

    public function getAdditivity(): bool
    {
        return $this->additive;
    }

    public function getEffectiveLevel(): ?LoggerLevel
    {
        for ($logger = $this; $logger !== null; $logger = $logger->getParent()) {
            if ($logger->getLevel() !== null) {
                return $logger->getLevel();
            }
        }

        return null;
    }

    public function getLevel(): ?LoggerLevel
    {
        return $this->level;
    }

    public function setLevel(LoggerLevel $level = null): void
    {
        $this->level = $level;
    }

    public function isAttached(LoggerAppender $appender): bool
    {
        return isset($this->appenders[$appender->getName()]);
    }

    public function setParent(Logger $logger): void
    {
        $this->parent = $logger;
    }

    public static function getHierarchy(): LoggerHierarchy
    {
        if (is_null(static::$hierarchy)) {
            $loggerRoot = new LoggerRoot();
            static::$hierarchy = new LoggerHierarchy($loggerRoot);
        }

        return static::$hierarchy;
    }

    public static function getLogger(string $name): Logger
    {
        if (!static::isInitialized()) {
            static::configure();
        }

        return static::getHierarchy()->getLogger($name);
    }

    public static function getRootLogger(): LoggerRoot
    {
        if (!static::isInitialized()) {
            static::configure();
        }

        return static::getHierarchy()->getRootLogger();
    }

    public static function clear(): void
    {
        static::getHierarchy()->clear();
    }

    public static function resetConfiguration(): void
    {
        static::getHierarchy()->resetConfiguration();
        static::getHierarchy()->clear();
        static::$initialized = false;
    }

    public static function exists(string $name): bool
    {
        return static::getHierarchy()->exists($name);
    }

    public static function getCurrentLoggers(): array
    {
        return static::getHierarchy()->getCurrentLoggers();
    }

    public static function configure(
        string|array $configuration = null,
        string|LoggerConfigurator $configurator = null
    ): void {
        static::resetConfiguration();
        $configurator = static::getConfigurator($configurator);
        $configurator->configure(static::getHierarchy(), $configuration);
        static::$initialized = true;
    }

    private static function getConfigurator(string|LoggerConfigurator $configurator = null): mixed
    {
        if ($configurator === null) {
            return new LoggerConfiguratorDefault();
        }

        if (is_object($configurator)) {
            return $configurator;
        }

        if (!class_exists($configurator)) {
            $log  = "log4php: Specified configurator class $configurator does not exist. ";
            $log .= "Reverting to default configurator.";
            trigger_error($log, E_USER_WARNING);
            return new LoggerConfiguratorDefault();
        }

        $instance = new $configurator();

        if (!($instance instanceof LoggerConfigurator)) {
            $log  = "log4php: Specified configurator class $configurator does not implement the ";
            $log .= "LoggerConfigurator interface. Reverting to default configurator.";
            trigger_error($log, E_USER_WARNING);
            return new LoggerConfiguratorDefault();
        }

        return $instance;
    }

    public static function isInitialized(): bool
    {
        return static::$initialized;
    }
}
