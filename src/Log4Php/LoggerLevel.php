<?php

namespace Log4Php;

class LoggerLevel
{
    public const int OFF   = 2147483647;
    public const int FATAL = 50000;
    public const int ERROR = 40000;
    public const int WARN  = 30000;
    public const int INFO  = 20000;
    public const int DEBUG = 10000;
    public const int TRACE = 5000;
    public const int ALL   = -2147483647;

    private static array $levelMap = [];

    private function __construct(
        private int $level,
        private string $levelStr,
        private int $syslogEquivalent
    ) {
    }

    public function equals(LoggerLevel $other): bool
    {
        if ($this->level === $other->getLevel()) {
            return true;
        }

        return false;
    }

    public static function getLevelOff(): LoggerLevel
    {
        if (!isset(static::$levelMap[self::OFF])) {
            static::$levelMap[static::OFF] = new LoggerLevel(static::OFF, 'OFF', LOG_ALERT);
        }

        return static::$levelMap[static::OFF];
    }

    public static function getLevelFatal(): LoggerLevel
    {
        if (!isset(static::$levelMap[static::FATAL])) {
            static::$levelMap[self::FATAL] = new LoggerLevel(static::FATAL, 'FATAL', LOG_ALERT);
        }

        return static::$levelMap[static::FATAL];
    }

    public static function getLevelError(): LoggerLevel
    {
        if (!isset(static::$levelMap[static::ERROR])) {
            static::$levelMap[static::ERROR] = new LoggerLevel(static::ERROR, 'ERROR', LOG_ERR);
        }

        return static::$levelMap[static::ERROR];
    }

    public static function getLevelWarn(): LoggerLevel
    {
        if (!isset(static::$levelMap[static::WARN])) {
            static::$levelMap[static::WARN] = new LoggerLevel(static::WARN, 'WARN', LOG_WARNING);
        }

        return self::$levelMap[self::WARN];
    }

    public static function getLevelInfo(): LoggerLevel
    {
        if (!isset(static::$levelMap[static::INFO])) {
            static::$levelMap[static::INFO] = new LoggerLevel(static::INFO, 'INFO', LOG_INFO);
        }

        return static::$levelMap[static::INFO];
    }

    public static function getLevelDebug(): LoggerLevel
    {
        if (!isset(static::$levelMap[static::DEBUG])) {
            static::$levelMap[static::DEBUG] = new LoggerLevel(static::DEBUG, 'DEBUG', LOG_DEBUG);
        }

        return static::$levelMap[static::DEBUG];
    }

    public static function getLevelTrace(): LoggerLevel
    {
        if (!isset(static::$levelMap[static::TRACE])) {
            static::$levelMap[static::TRACE] = new LoggerLevel(static::TRACE, 'TRACE', LOG_DEBUG);
        }

        return static::$levelMap[static::TRACE];
    }

    public static function getLevelAll(): LoggerLevel
    {
        if (!isset(static::$levelMap[static::ALL])) {
            static::$levelMap[static::ALL] = new LoggerLevel(static::ALL, 'ALL', LOG_DEBUG);
        }

        return static::$levelMap[static::ALL];
    }

    public function getSyslogEquivalent(): int
    {
        return $this->syslogEquivalent;
    }

    public function isGreaterOrEqual(LoggerLevel $other): bool
    {
        if ($this->level >= $other->getLevel()) {
            return true;
        }

        return false;
    }

    public function toString(): string
    {
        return $this->levelStr;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toInt(): int
    {
        return $this->level;
    }

    public static function toLevel(int|string $arg, ?LoggerLevel $defaultLevel = null): ?LoggerLevel
    {
        if (is_int($arg)) {
            return static::intLevel($arg, $defaultLevel);
        }

        return static::strLevel($arg, $defaultLevel);
    }

    private static function intLevel(int|string $arg, ?LoggerLevel $defaultLevel = null): ?LoggerLevel
    {
        return match ($arg) {
            static::ALL   => static::getLevelAll(),
            static::TRACE => static::getLevelTrace(),
            static::DEBUG => static::getLevelDebug(),
            static::INFO  => static::getLevelInfo(),
            static::WARN  => static::getLevelWarn(),
            static::ERROR => static::getLevelError(),
            static::FATAL => static::getLevelFatal(),
            static::OFF   => static::getLevelOff(),
            default       => $defaultLevel
        };
    }

    private static function strLevel(int|string $arg, ?LoggerLevel $defaultLevel = null): ?LoggerLevel
    {
        return match (strtoupper((string)$arg)) {
            'ALL'   => static::getLevelAll(),
            'TRACE' => static::getLevelTrace(),
            'DEBUG' => static::getLevelDebug(),
            'INFO'  => static::getLevelInfo(),
            'WARN'  => static::getLevelWarn(),
            'ERROR' => static::getLevelError(),
            'FATAL' => static::getLevelFatal(),
            'OFF'   => static::getLevelOff(),
            default => $defaultLevel
        };
    }

    public function getLevel(): int
    {
        return $this->level;
    }
}
